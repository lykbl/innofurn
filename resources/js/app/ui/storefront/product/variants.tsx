'use client';

import { ReactNode } from "react";
import Image from "next/image";

export type Variant = {
  id: number,
  href: string,
  name: string,
  price: number,
}

interface VariantProp {
  variant: Variant,
  handleClick: Function,
}

const Variant = ({variant: {id, href, name}, handleClick}: VariantProp) => {
  return (
    <button
      className='border-2 border-black hover:border-blue-500 rounded'
      onClick={() => handleClick(id)}
    >
      <Image src={href} alt={name} width={100} height={100} />
    </button>
  );
}

interface VariantsProps {
  type: string,
  options: Variant[],
  selectedOption: Variant,
  handleSelect: Function,
}
const Variants = ({ type, options, selectedOption, handleSelect }: VariantsProps): ReactNode => {
  return (
    <div className='flex gap-2 flex-col'>
      {/*TODO use label instead of id*/}
      <h3>Choose {type}: {selectedOption.name}</h3>
      <div className='flex gap-2'>
        {options.map(variant => <Variant key={variant.id} variant={variant} handleClick={() => handleSelect(variant.id)} />)}
      </div>
    </div>
  );
}

export default Variants;
