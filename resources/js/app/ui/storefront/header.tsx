import {
  ArrowDown,
  Hamburger,
  HeaderLogo,
  Profile,
  Search, ShoppingBag,
} from '@/public/icons/icons';

const BasicInput = (props) => {
  return (
    <input
      {...props}
    />
  )
}

export default async function Header() {
  return (
    <div className='flex justify-between max-w-screen-2xl mx-auto py-2'>
      <div className='flex items-center'>
        <button className='flex items-center mr-4 hover:bg-slate-100 py-1 px-2'>
          <Hamburger  />
          Menu
        </button>
        <button className='hover:bg-slate-100 p-2'>
          <HeaderLogo />
        </button>
      </div>
      <div className='flex items-center justify-between rounded border-black border-2 w-3/5'>
        <button className='flex bg-blue-500 p-3 h-full text-white hover:bg-blue-700'>
          All Departments...
          <ArrowDown className='stroke-white' />
        </button>
        <BasicInput className='w-full p-0' placeholder='Find Anything Home...' />
        <button className='bg-blue-500 p-3 hover:bg-blue-700'>
          <Search size={24} className='stroke-white' />
        </button>
      </div>
      <div className='flex items-center'>
        <button className='flex rounded hover:bg-slate-100 p-2'>
          Sign In
          <Profile className='ml-2' />
        </button>
        <button className='flex rounded hover:bg-slate-100 p-2'>
          Cart
          <ShoppingBag className='ml-2' />
        </button>
      </div>
    </div>
  );
}
