import '@/app/styles/global.scss';
import Header from '@/app/ui/storefront/layout/header';
import React from "react";
import Subheader from "@/app/ui/storefront/layout/subheader";

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="en" className='h-screen'>
      <body className='antialiased text-base flex items-center flex-col'>
        <header className='w-full'>
          <Header />
          <Subheader />
        </header>
        <main className='max-w-screen-2xl w-full px-2 mx-auto'>
          {children}
        </main>
        <footer>
          {/* <Footer /> */}
        </footer>
      </body>
    </html>
  );
}
