'use client';

import Link from "@/app/ui/common/link";
import Carousel from "@/app/ui/animated/carousel/carousel";
import Rating, { RATING_STYLES } from "@/app/ui/storefront/product/rating";
import { Button, BUTTON_STYLES } from "@/app/ui/common/button";
import { formatCurrency } from "@/app/lib/utils";
import Image from "next/image";

const carouselItems = Array(10).fill(null).map((el, index) => {
  return (
    <div
      key={index}
      className='flex flex-col gap-1 rounded bg-blue-100 p-2 w-max'
    >
      <div className='pointer-events-none'>
        <Image
          className='rounded'
          src='/sample-kitchen-image-2.jpg'
          alt='image'
          width={275}
          height={275}
        />
        <h3>Atley Throw Pillow</h3>
        <div className='flex items-center'>
          <span className='text-lg mr-2'>{formatCurrency(3499)}</span>
          <span className='text-zinc-500 line-through text-sm'>{formatCurrency(4799)}</span>
        </div>
      </div>
      <Rating starSize={20} totalRating={4.6} reviewsCount={299} style={RATING_STYLES.WITH_RATING}/>
      <Button style={BUTTON_STYLES.BLUE} className='w-full'>Select Options</Button>
    </div>
  )
})

const MoreFromCreator = () => {
  return (
    <div className='flex-col w-full gap-2 flex py-2 border-y border-black'>
      <div className='flex items-center justify-between'>
        <h2>
          More from
          <Link className='ml-2' href='/brand/test'>
            TestBrand
          </Link>
        </h2>
        <Link href='/search?brand=test' className='text-lg'>See All</Link>
      </div>
      <Carousel
        className='flex justify-between'
        items={carouselItems}
      />
    </div>
  );
}

export default MoreFromCreator;
