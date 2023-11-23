import '@/app/styles/global.scss';
import { quicksand } from '@/app/ui/fonts';
import Header from '@/app/ui/storefront/header';
import CategoriesNav from '@/app/ui/storefront/categories-nav';

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="en">
      <body className='antialiased text-base flex items-center flex-col'>
        <header className='w-full'>
          {/* <FeaturedLinks /> */}
          <Header />
          <CategoriesNav />
        </header>
        <main className='max-w-screen-2xl w-full mx-auto'>
          {children}
        </main>
        <footer>
          {/* <Footer /> */}
        </footer>
      </body>
    </html>
  );
}
