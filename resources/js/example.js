/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import gql from "graphql-tag";
import Pusher from "pusher-js";
import { ApolloLink } from "apollo-link";

import { ApolloClient, concat, createHttpLink, InMemoryCache } from "@apollo/client";
import PusherLink from "./chat/pusher.js";

// Pusher.logToConsole = true;

const pusherLink = new PusherLink({
  pusher: new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
    authEndpoint: `http://localhost/graphql/subscriptions/auth`,
    auth: {
      headers: {},
    },
  }),
});

const link = ApolloLink.from([
  pusherLink,
  createHttpLink({ uri: `/graphql` })
]);

const authMiddleware = new ApolloLink((operation, forward) => {
  // add the authorization to the headers
  operation.setContext(({ headers = {} }) => ({
    headers: {
      ...headers,
      'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
      // authorization: localStorage.getItem('token') || null,
    }
  }));

  return forward(operation);
});

const client = new ApolloClient({
  link: concat(authMiddleware, link),
  cache: new InMemoryCache(),
});
