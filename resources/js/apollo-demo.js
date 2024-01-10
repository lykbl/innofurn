import axios from 'axios';
import { ApolloLink } from "apollo-link";
import { ApolloClient, createHttpLink, InMemoryCache, gql } from "@apollo/client";

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
const authLink = new ApolloLink((operation, forward) => {
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
  link: ApolloLink.from([
    createHttpLink({ uri: `/graphql` }),
    authLink,
  ]),
  cache: new InMemoryCache(),
});

const CHECK_ME_QUERY = gql(`
  query CheckMe {
    checkMe {
      id
    }
  }
`);

async function main() {
  try {
    const response = await client.query({
      query: CHECK_ME_QUERY,

  });
    console.log('Async', response);

    return response;
  } catch (e) {
    console.log('Error detected', e);
  }
}

console.log(main());
