import Pusher from 'pusher-js';
import { ApolloClient, createHttpLink, gql, InMemoryCache } from "@apollo/client";
import { ApolloLink } from "apollo-link";
import { setContext } from "@apollo/client/link/context";
import Cookies from 'js-cookie';
import PusherLink from "./pusher.js";

let csrfRequesting = false;
const asyncAuthLink = setContext(
  () =>
    new Promise(async (success, fail) => {
      if (!Cookies.get('XSRF-TOKEN') && !csrfRequesting) {
        csrfRequesting = true;
        await fetch("http://localhost/sanctum/csrf-cookie", {
          method: "GET",
          credentials: "include",
        });
      }

      const csrfCookie = Cookies.get("XSRF-TOKEN");
      csrfCookie
        ? success({
          headers: {
            "X-XSRF-TOKEN": csrfCookie,
          },
        })
        : fail("No XSRF-TOKEN cookie");
    }),
);

const pusherLink = new PusherLink({
  pusher: new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
    authEndpoint: `http://localhost/graphql/subscriptions/auth`,
    auth: {
      headers: {},
    },
  }),
});

const chatSubscriptionProvider = new ApolloClient({
  link: ApolloLink.from([
    asyncAuthLink, //TODO can be removed?
    pusherLink,
    createHttpLink({ uri: `/graphql` }),
  ]),
  cache: new InMemoryCache(),
})

document.addEventListener('DOMContentLoaded', async function() {
  const subscription = chatSubscriptionProvider.subscribe({
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
})
