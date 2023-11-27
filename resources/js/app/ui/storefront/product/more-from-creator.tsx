import Link from "@/app/ui/common/link";
import Image from "next/image";
import { formatCurrency } from "@/app/lib/utils";
import Rating from "@/app/ui/storefront/product/rating";
import { Button } from "@/app/ui/common/button";

const MoreFromCreator = () => {
  return (
    <div className='flex-col w-full gap-2 flex'>
      <div className='flex items-center justify-between'>
        <h2>
          More from
          <Link className='ml-2' href='/brand/mercer1'>
            Mercer41
          </Link>
        </h2>
        <Link href='/search?brand=mercer' className='text-lg'>See All</Link>
      </div>
      <div className='flex gap-2'>
        <div className='flex gap-5'>
          {Array(5).fill(null).map((el, index) => (
            <div className='flex-col'>
              <Image
                className='rounded'
                src='/sample-kitchen-image-2.jpg'
                alt='image'
                width={268}
                height={268}
              />
              <h3>Atley Throw Pillow</h3>
              <div className='flex items-center'>
                <span className='text-lg mr-2'>{formatCurrency(3499)}</span>
                <span className='text-zinc-500 line-through text-sm'>{formatCurrency(4799)}</span>
              </div>
              <Rating />
              <Button>Select Options</Button>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}

export default MoreFromCreator;
