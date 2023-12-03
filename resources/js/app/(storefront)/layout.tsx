import '@/app/styles/global.scss';
import Header from '@/app/ui/storefront/layout/header';
import React from 'react';
import Subheader from '@/app/ui/storefront/layout/subheader';
import Footer from '@/app/ui/storefront/layout/footer';
import { inter } from "@/app/ui/fonts";

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
    <header className='w-full'>
      <Header />
      <Subheader />
    </header>
    <main className='max-w-screen-2xl w-full px-2 mx-auto'>
      {children}
    </main>
    <footer className='py-16 bg-neutral-100 w-full mt-8'>
      <Footer />
    </footer>
    </body>
    </html>
  );
}
