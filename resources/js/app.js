import Picker from "vanilla-picker";
import { apolloClient } from "./apollo-client.js";
import { gql } from "@apollo/client";

window.Picker = Picker;
window.ApolloClient = apolloClient;
window.gql = gql;
