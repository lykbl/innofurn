import { gql } from "@apollo/client";
import apolloClient from "../apollo-client.js";
document.addEventListener('DOMContentLoaded', async function() {
  //TODO disconnects?
  const subscription = apolloClient.subscribe({
    query: gql`
        subscription {
            updateChatRoom {
                id
                body
            }
        }
    `,
  }).subscribe(
    (fetchResult) => {
      const newMessage = fetchResult.data.updateChatRoom;
      if (newMessage) {
        Livewire.emit('updateChatRoom', newMessage);
      }
    },
    (e) => console.log(e),
    () => console.log('DONE')
  );

  const mutation = await apolloClient.mutate({
    mutation: gql`
        mutation SendMessage ($input: CreateChatMessageInput!) {
            sendMessageToChatRoom(input: $input) {
                id
                body
            }
        }
    `,
    variables: {
      input: {
        body: 'from hub',
        chatRoomId: 1
      }
    }
  });
})
