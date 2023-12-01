import { FC, ReactNode, Ref } from "react";
import {  motion } from "framer-motion";

interface ItemsProps {
  items: ReactNode[],
  childRef: Ref<HTMLDivElement>,
  gap: number,
  itemsCount: number,
}

const Items: FC<ItemsProps> = ({ items, childRef, gap, itemsCount }) => {
  return (items.map((item, index) => (
    <motion.div
      className='w-full'
      ref={index === 0 ? childRef : null}
      key={index}
      style={{
        marginRight: index === itemsCount - 1 ? 0 : `${gap}px`,
      }}
    >
      {item}
    </motion.div>
  )));
}

export default Items;
