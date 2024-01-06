import '@/app/styles/global.scss';
import Header from '@/app/ui/storefront/layout/header';
import React from 'react';
import Subheader from '@/app/ui/storefront/layout/subheader';
import Footer from '@/app/ui/storefront/layout/footer';
import { inter } from "@/app/ui/fonts";
import { getClient } from "@/app/lib/apollo-client";

import { gql } from "@apollo/client";
import { ApolloWrapper } from "@/app/lib/apollo-provider";

const query = gql`
    query rootCollections {
        rootCollections {
            id
        }
    }
`;

const Collections = async () => {
  const rootCollections = await getClient().query({
    query,
    context: {
      fetchOptions: {
        next: { revalidate: 30 },
      },
    },
  });
  console.log(rootCollections.data.rootCollections)

  return (
    <div>
      Collections!
      {rootCollections.data.rootCollections.map((collection: any) =>
        <div>{collection.name}</div>
      )}
    </div>
  );
}

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html
      lang='en'
      className='h-screen'
    >
    <body className={`antialiased text-base flex items-center flex-col ${inter.className}`}>
      <ApolloWrapper>
        <header className='w-full'>
          <Header />
          <Subheader />
          <Collections />
        </header>
        <main className='max-w-screen-2xl w-full px-2 mx-auto'>
          {children}
        </main>
        <footer className='py-16 bg-neutral-100 w-full mt-8'>
          <Footer />
        </footer>
      </ApolloWrapper>
    </body>
    </html>
  );
}
