import { HeaderLogo } from '@/app/ui/logo';
import { Button, BUTTON_STYLES } from '@/app/ui/common/button';
import Link from "@/app/ui/common/link";
import {
  BiSearchAlt,
  BiShoppingBag,
  BiUser,
} from 'react-icons/bi';
import { IoIosArrowDown } from 'react-icons/io';
import BasicInput from '@/app/ui/common/input';

export default async function Header() {
  return (
    <div className='border-b-2'>
      <div className='flex justify-between max-w-screen-2xl mx-auto py-2 px-2 items-center'>
        <div className='flex items-center w-1/5'>
          <Link href='/' className='p-2 rounded border-transparent border hover:border-black'>
            <HeaderLogo />
          </Link>
        </div>
        <div className='flex items-center justify-between rounded border-black border-2 w-3/5 h-min'>
          <Button style={BUTTON_STYLES.BLUE} className='flex items-center min-w-max rounded-sm rounded-r-none'>
            <p>All Departments...</p>
            <IoIosArrowDown className='stroke-white' />
          </Button>
          <BasicInput className='w-full' placeholder='Find Anything Home...' />
          <Button style={BUTTON_STYLES.BLUE} className='rounded-sm rounded-l-none'>
            <BiSearchAlt size={24} className='stroke-white' />
          </Button>
        </div>
        <div className='flex items-center w-1/5 justify-end'>
          <Button className='mr-2 flex'>
            Sign In
            <BiUser size={24} className='ml-2' />
          </Button>
          <Button className='flex'>
            Cart
            <BiShoppingBag className='ml-2' size={24} />
          </Button>
        </div>
      </div>
    </div>
  );
}
