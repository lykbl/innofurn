import { Button } from '@/app/ui/common/button';
import {
  FaCcAmex,
  FaCcMastercard,
  FaCcVisa,
  FaFacebook,
  FaInstagram,
  FaTiktok,
  FaYoutube
} from "react-icons/fa";
import { IoIosArrowDown } from "react-icons/io";

const MediaIcons = [
  <FaFacebook size={20} />,
  <FaInstagram size={20} />,
  <FaTiktok size={20} />,
  <FaYoutube size={20} />,
];

const IntegratedPaymentsIcons = [
  <FaCcVisa size={20} />,
  <FaCcMastercard size={20} />,
  <FaCcAmex size={20} />,
];

const Footer = () => (
  <div className='max-w-screen-2xl flex flex-col w-full mx-auto font-medium text-neutral-700 text-sm gap-16'>
    <div className='flex justify-between w-full'>
      <div className='flex flex-col gap-8 w-1/5 mr-6'>
        <div className='flex flex-col gap-2'>
          <p className='font-semibold text-base text-black mb-4'>Join InnoFurn Family</p>
          <p>Bring your ideas to life with special discounts, inspiration and lots of
            good things in store. It's all
            free.</p>
          <Button className='w-max px-4'>Join or log in</Button>
        </div>
        <div className='flex flex-col gap-2'>
          <p className='font-semibold text-base text-black mb-4'>We support you</p>
          <p>You can do all by yourself or get help from us. Our services make your
            life easier.</p>
          <Button className='w-max px-4'>Go to customer service</Button>
        </div>
      </div>
      <div className='flex flex-col gap-4 w-1/5'>
        <p className='font-semibold text-black'>Shop & plan</p>
        <div className='flex flex-col gap-4 mt-2'>
          <p>Locations</p>
          <p>All InnoFurn products</p>
          <p>Offers</p>
          <p>Planners</p>
          <p>Planning consultation</p>
          <p>Swedish restaurant & food (German)</p>
          <p>Give us your feedback</p>
        </div>
      </div>
      <div className='flex flex-col gap-4 w-1/5'>
        <p className='font-semibold text-black'>Services</p>
        <div className='flex flex-col gap-4 mt-2'>
          <p>Delivery</p>
          <p>Click & collect</p>
          <p>Rental</p>
          <p>Assembly</p>
          <p>Removal & recycling</p>
          <p>Repair & Longer Life</p>
          <p>Finance options</p>
          <p>Guarantee (German)</p>
        </div>
      </div>
      <div className='flex flex-col gap-4 w-1/5'>
        <p className='font-semibold text-black'>Help & support</p>
        <div className='flex flex-col gap-4 mt-2'>
          <p>Services</p>
          <p>Purchase history</p>
          <p>Track my order</p>
          <p>Cancel my order</p>
          <p>Change delivery details</p>
          <p>Return & claims</p>
          <p>Express return</p>
          <p>Help & contact</p>
        </div>
      </div>
      <div className='flex flex-col gap-4 w-1/5'>
        <p className='font-semibold text-black'>About InnoFurn</p>
        <div className='flex flex-col gap-4 mt-2'>
          <p>InnoFurn Family</p>
          <p>InnoFurn for Business</p>
          <p>This is InnoFurn</p>
          <p>Jobs & career</p>
          <p>Newsroom (German)</p>
          <p>Product recalls</p>
          <p>InnoFurn apps</p>
          <p>InnoFurn terms & conditions</p>
        </div>
      </div>
    </div>
    <div className='flex justify-between'>
      <div className='flex justify-between gap-4'>
        {[...MediaIcons, ...IntegratedPaymentsIcons].map((icon, i) => (
          <div key={i} className='flex items-center rounded-full p-2 bg-white border border-neutral-300 hover:border-neutral-500'>
            {icon}
          </div>
        ))}
      </div>
      <div className='flex'>
        <Button className='flex gap-2 items-center'><span>English</span><IoIosArrowDown size={24}/></Button>
      </div>
    </div>
  </div>
);

export default Footer;