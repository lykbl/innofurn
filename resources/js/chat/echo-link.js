import { Observable, ApolloLink } from "@apollo/client/core";

// The presence channel interface does not have the channel methods,
// but in reality the actual object does, so I try to fix this here.
function subscribeToEcho(
    echoClient,
    channelName,
    observer,
) {
    const channel = echoClient.private(
        channelName.replace(/^private\-/, "")
    );
    channel.listen(".lighthouse-subscription", (result) =>
        observer.next(result.data)
    );
}

function unsubscribe(echoClient, getChannelName) {
  const channelName = getChannelName();
  if (channelName) {
    console.log('unsubscibing')
    echoClient.leave(channelName);
  }
}

function createSubscriptionHandler(
    echoClient,
    operation,
    observer,
    setChannelName,
) {
    return (data) => {
      console.log('got data')
        const operationDefinition =
            operation.query.definitions.find(
                (definitionNode) => definitionNode.kind === "OperationDefinition"
            );
        const fieldNode =
            operationDefinition.selectionSet.selections.find(
                (definitionNode) => definitionNode.kind === "Field"
            );
        const subscriptionName = fieldNode.name.value;
        // const lighthouseVersion =
        //     data?.extensions?.lighthouse_subscriptions?.version;

        const lighthouseVersion = 2;
        console.log('version', lighthouseVersion)
        const channelName =
            lighthouseVersion === 2
                ? data?.extensions?.lighthouse_subscriptions?.channel
                : data?.extensions?.lighthouse_subscriptions?.channels?.[
                    subscriptionName
                    ];

        console.log('channel name', channelName)

        if (channelName) {
            console.log('saetting channel name', channelName)
            setChannelName(channelName);
            subscribeToEcho(echoClient, channelName, observer);
        } else {
            console.log('doing next')
            observer.next(data);
            observer.complete();
        }
    };
}

function createRequestHandler(echoClient) {
  console.log('creating request handler', echoClient)
    return (operation, forward) => {
        let channelName;

        return new Observable((observer) => {
          console.log('inside observable')
            forward(operation).subscribe(
                createSubscriptionHandler(
                    echoClient,
                    operation,
                    observer,
                    (name) => (channelName = name)
                ),
                error => observer.error(error)
            );

            return () => unsubscribe(echoClient, () => channelName);
        });
    };
}

export function createLighthouseSubscriptionLink(echoClient) {
    return new ApolloLink(createRequestHandler(echoClient));
}
