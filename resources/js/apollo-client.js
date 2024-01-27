import Pusher from 'pusher-js';
import { ApolloClient, createHttpLink, gql, InMemoryCache } from "@apollo/client";
import { ApolloLink } from "apollo-link";
import { setContext } from "@apollo/client/link/context";
import Cookies from 'js-cookie';
import PusherLink from "./chat/pusher-link.js";
import Echo from "laravel-echo";
// import { createLighthouseSubscriptionLink } from "./chat/echo-link.js";

let csrfRequesting = false;
const asyncAuthLink = setContext(
  () =>
    new Promise(async (success, fail) => {
      const csrfCookie = Cookies.get("XSRF-TOKEN");
      csrfCookie
        ? success({
          headers: {
            "X-XSRF-TOKEN": csrfCookie,
            'credentials': 'include',
          },
        })
        : fail("No XSRF-TOKEN cookie");
    }),
);

const pusherLink = new PusherLink({
  pusher: new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'eu',
    authEndpoint: `${import.meta.env.APP_HOST}/graphql/subscriptions/auth`,
    auth: {
      headers: {},
    },
  }),
});

// const echoClient = window.Echo || new Echo({
//   broadcaster: 'pusher',
//   key: import.meta.env.VITE_PUSHER_APP_KEY,
//   cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
//   forceTLS: true
// });

// const echoLink = createLighthouseSubscriptionLink(echoClient)

const apolloClient = new ApolloClient({
  link: ApolloLink.from([
    asyncAuthLink, //TODO can be removed?
    // echoLink,
    pusherLink,
    createHttpLink({
      uri: `${import.meta.env.APP_HOST}/graphql`,
      credentials: 'include',
    }),
  ]),
  cache: new InMemoryCache(),
})

// window.ApolloClient = apolloClient;
// window.gql = gql;
