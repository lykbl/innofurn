'use client';

import { IoIosHeart, IoIosHome } from "react-icons/io";
import { BiMinus, BiPlus, BiSearchAlt } from "react-icons/bi";
import { useState } from "react";
import { Button, BUTTON_STYLES } from "@/app/ui/common/button";
import { formatCurrency } from "@/app/lib/utils";

interface CartControlProps {
  price: number;
}

const CartControl = ({ price }: CartControlProps) => {
  const [count, setCount] = useState<number>(1);
  const increment = () => setCount(prev => prev + 1);
  const decrement = () => setCount(prev => prev - 1 || prev);
  const totalPrice = count > 1 ? price * count : price;

  return (
    <div className='flex flex-col p-2 w-full bg-blue-100 rounded gap-2'>
      <Button style={BUTTON_STYLES.BLUE}>
        Add to cart
      </Button>
      <div className='flex gap-4'>
        <div className='outline outline-1 outline-black rounded bg-white flex justify-center w-1/3 gap-6 items-center'>
          <Button
            className='p-0 h-min'
            onClick={decrement}
          >
            <BiMinus size={24} />
          </Button>
          <span>{formatCurrency(price)} x {count}</span>
          <Button
            className='p-0 h-min'
            onClick={increment}
          >
            <BiPlus size={24} />
          </Button>
        </div>
        <span className='text-3xl w-1/3 text-center'>
          {formatCurrency(totalPrice)}
        </span>
        <div className='flex gap-2 w-1/3 justify-end'>
          <Button
            className='bg-white text-pink-500 border-pink-500 hover:border-pink-700 hover:text-pink-700'
          >
            <IoIosHeart
              size={24}
            />
          </Button>
          <Button
            className='text-blue-600 bg-white border-blue-600 hover:border-blue-700 hover:text-blue-700'
          >
            <BiSearchAlt
              size={24}
            />
          </Button>
        </div>
      </div>
    </div>
  );
}

export default CartControl;
