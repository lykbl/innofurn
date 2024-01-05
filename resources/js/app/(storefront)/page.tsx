'use client';

import Link from "@/app/ui/common/link";
// import { useSuspenseQuery } from "@apollo/client"; //TODO REVERT?
import { useSuspenseQuery } from "@apollo/experimental-nextjs-app-support/ssr";
// import { gql } from "@/app/__generated__";
import { gql } from "@apollo/client";
import { useEffect } from "react";

const GET_COLLECTIONS = gql(/* GraphQL */`
    query GetRootCollections {
        rootCollections {
            id
        }
    }
`);

export default function Page() {
  const { data } = useSuspenseQuery(
    GET_COLLECTIONS as any,
    {
      variables: {},
    }
  );

  console.log(data);

  return (
    <>
      Main page
      <Link href='/product/test'>Product page</Link>
    </>
  );
}
