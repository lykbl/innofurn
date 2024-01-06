'use client';

import Link from "@/app/ui/common/link";
// import { useSuspenseQuery } from "@apollo/client"; //TODO REVERT?
import { useSuspenseQuery } from "@apollo/experimental-nextjs-app-support/ssr";
import { gql } from '@/app/gql/gql';

const GET_COLLECTIONS = gql(/* GraphQL */`
    query GetRootCollections {
        rootCollections {
            id
            name
        }
    }
`);

export default function Page() {
  const { data } = useSuspenseQuery(
    GET_COLLECTIONS,
    {
      variables: {},
    }
  );

  return (
    <>
      Main page
      <Link href='/product/test'>Product page</Link>
      <div>
        {data?.rootCollections?.map((collection) => (
          <div key={collection.id}>{collection.name}</div>
        ))}
      </div>
    </>
  );
}
