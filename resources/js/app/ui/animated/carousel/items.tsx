import { FC, ReactNode, Ref } from "react";
import {  motion } from "framer-motion";

interface ItemsProps {
  items: ReactNode[],
  childRef: Ref<HTMLDivElement>,
  gap: number,
}

const Items: FC<ItemsProps> = ({ items, childRef, gap }) => {
  return (items.map((item, index) => (
    <motion.div
      ref={index === 0 ? childRef : null}
      key={index}
      style={{
        marginRight: `${gap}px`,
      }}
    >
      {item}
    </motion.div>
  )));
}

export default Items;
