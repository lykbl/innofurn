'use client';

import Input from "@/app/ui/common/input";
import { Stars } from "@/app/ui/storefront/product/rating";
import { calculatePercentage } from "@/app/lib/utils";
import { motion, useInView } from 'framer-motion';
import { useRef } from "react";
import clsx from "clsx";
import { Button, BUTTON_STYLES } from "@/app/ui/common/button";
import Image from "next/image";
import Carousel from "@/app/ui/animated/carousel/carousel";

const reviewImages = Array(10).fill(null).map((_, index) => (
  <div
    key={index}
    className='min-w-[200px]'
  >
    <Image
      className='rounded'
      width={200}
      height={200}
      alt='Review imaeg'
      src='/sample-kitchen-image-2.jpg'
    />
  </div>
));

const ratings = [
  { 'count': 280, 'rating': 5 },
  { 'count': 20, 'rating': 4 },
  { 'count': 10, 'rating': 3 },
  { 'count': 5, 'rating': 2 },
  { 'count': 2, 'rating': 1 },
]

interface RatingBarProps {
  fillTo: number,
  className?: string,
}

const RatingBar = ({ fillTo, className }: RatingBarProps) => {
  const ref = useRef<HTMLDivElement>(null);
  const isInView = useInView(ref, { once: true, amount: 0.3 });

  return (
    <div
      ref={ref}
      className={clsx(
        'border border-blue-600 hover:border-blue-700 rounded drop-shadow-lg bg-gray-100',
        className
      )}
    >
      <motion.div
        className='h-full w-0 bg-blue-600 group-hover:bg-blue-700 duration-300'
        animate={isInView ? { width: `${fillTo}%` } : { width: 0 }}
      />
    </div>
  );
}

interface ReviewsBreakdownProps {
  totalCount: number,
}

const ReviewsBreakdown = ({ totalCount }: ReviewsBreakdownProps) => {
  return (
    <div className='flex flex-col w-1/5 bg-blue-100 rounded p-2 h-min'>
      <h2>Customer Reviews</h2>
      <div className='flex flex-col py-2 border-b border-black'>
        <div className='flex gap-2 text-lg'><Stars size={24} /> 4.8 of 5</div>
        <div className='flex flex-col'>
          {ratings.map((review, index) => (
            <Button
              className='group flex gap-2 w-full bg-transparent hover:bg-transparent hover:underline text-black hover:text-blue-600'
              style={BUTTON_STYLES.BLUE}
              key={index}
            >
              <span className='text-left w-1/5'>{`${review.rating} star`}</span>
              <RatingBar
                className='h-[24px] w-3/5'
                fillTo={calculatePercentage(review.count, totalCount)}
              />
              <span className='text-right w-1/5'>{calculatePercentage(review.count, totalCount)}%</span>
            </Button>
          ))}
        </div>
      </div>
      <div className='flex flex-col py-2 gap-2'>
        <h5 className='font-semibold'>Review this product</h5>
        <span className='text-sm'>Share your thoughts with other customers</span>
        <Button
          className='py-1'
          style={BUTTON_STYLES.BLUE}
        >Leave a review</Button>
      </div>
    </div>
  );
}

interface ReviewsSummaryProps {
}

const ReviewSample = () => (
  <div className='flex flex-col text-sm py-2 gap-1 border-b border-black mb-4 font-medium'>
    <div className='flex gap-1 items-center mb-2'>
      <span className='rounded-full bg-gray-500 w-[25px] h-[25px]' />
      <span className='text-md'>Dan</span>
    </div>
    <div className='flex items-center gap-1'>
      <Stars size={16} />
    </div>
    <p>
      <h4 className='text-lg font-semibold'>Nice Travel Size</h4>
    </p>
    <p>
      <span>Color: Blue</span>
      <span className='pl-2 ml-2 border-l border-black text-green-500'>Verified purchase</span>
    </p>
    <p> use this as a watercolor paint reference book. Each pocket can hold two swatches and keeps my variety of paint information readily at hand. I can quickly compare colors, light fastness, pigment numbers as the pockets are crystal clear. Nothing falls or slips out of the pockets and the materials are sturdier than most photo albums! I'm sure it would be perfect for what is was made for, but for me, it is the perfect library of my watercolor info.</p>
    <p className='text-gray-500 flex gap-2'>
      <span>Reviewed in the United States on October 9, 2023</span>
  </p>
    <div className='py-2'>
      <Carousel
        items={Array(10).fill(null).map((_, index) =>
          <Image
            key={index}
            className='rounded min-w-[100px]'
            width={100}
            height={100}
            src='/sample-kitchen-image-2.jpg'
            alt='customer pohoto'
          />
        )}
      />
    </div>
    <div className='flex gap-2 mt-2'>
      <Button style={BUTTON_STYLES.BLUE}>Helpful</Button>
      <Button>Report</Button>
    </div>
  </div>
);
const ReviewsSummary = ({}: ReviewsSummaryProps) => {
  return (
    <div className='flex flex-col w-3/5 bg-blue-100 rounded p-2'>
      <div className='flex flex-col gap-2 border-neutral-200'>
        <h3 className='font-medium'>Looking for specific info?</h3>
        <Input
          type='text'
          className='rounded border border-black border-solid'
          placeholder='Search in reviews, Q&A...'
        />
      </div>
      {/*<div className='py-2'>*/}
      {/*  <h4>Customers say</h4>*/}
      {/*  <p>Customers like the quality, protection, and storage space of the display album. They mention that it has a*/}
      {/*    sturdy build quality, clear pages, and holds cards well. Some say that the durable design helps protect*/}
      {/*    their collection, and that each pocket can hold two swatches.</p>*/}
      {/*  <span className='text-sm text-gray-500'>AI-generated from the text of customer reviews</span>*/}
      {/*</div>*/}
      <div className='flex flex-col'>
        <div className='py-2 border-b border-black'>
          <h3>Reviews with images</h3>
          <div className='py-2'>
            <Carousel items={reviewImages} controlsSize={20} />
          </div>
        </div>
        <div className='py-2'>
          <h3 className='text-2xl'>Top Reviews</h3>
          <div className='flex flex-col gap-2'>
            {Array(3).fill(null).map((_, index) => <ReviewSample key={index} />)}
            <Button>Load more reviews</Button>
          </div>
        </div>
      </div>
    </div>
  );
}

const Reviews = () => {
  const totalCount = 317;

  return (
    <section className='flex flex-col gap-2'>
      <div className='flex gap-4'>
        <ReviewsBreakdown totalCount={totalCount} />
        <ReviewsSummary />
      </div>
    </section>
  );
}

export default Reviews;
