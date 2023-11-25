import '@/app/styles/global.scss';
import Header from '@/app/ui/storefront/layout/header';

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
          {/*<FeaturedLinks />*/}
        </header>
        <main className='max-w-screen-2xl w-full mx-auto border-2 border-t-0'>
          {children}
        </main>
        <footer>
          {/* <Footer /> */}
        </footer>
      </body>
    </html>
  );
}
