import { FC, MouseEventHandler, ReactNode } from "react";
import { motion } from "framer-motion";
import { IoIosArrowBack, IoIosArrowForward } from "react-icons/io";
import clsx from "clsx";

interface ControlButtonProps {
  children: ReactNode;
  handleClick: MouseEventHandler<HTMLButtonElement>;
  className?: string;
}

const ControlButton = ({ children, handleClick, className }: ControlButtonProps) => (
  <motion.button
    className={clsx(
      'absolute top-[50%] bg-blue-500 border-blue-500 hover:bg-blue-700 hover:border-blue-700 p-2 rounded text-white hover:drop-shadow-lg',
      className,
    )}
    whileTap={{ scale: 0.8 }}
    initial={{ y: '-50%' }}
    onClick={handleClick}
  >
    {children}
  </motion.button>
);

interface ControlsProps {
  prev: MouseEventHandler<HTMLButtonElement>,
  next: MouseEventHandler<HTMLButtonElement>,
  currentIndex: number,
  size: number,
  itemsCount: number,
}

const Controls: FC<ControlsProps> = ({
  prev, next, currentIndex, size, itemsCount
}) => {
  return (
    <div>
      {currentIndex > 0 &&
        <ControlButton
          handleClick={prev}
          className='left-2'
        >
          <IoIosArrowBack size={24} />
        </ControlButton>
      }
      {
        (currentIndex + size < itemsCount - 1) && <ControlButton
          handleClick={next}
          className='right-2'
        >
          <IoIosArrowForward size={24} />
        </ControlButton>
      }
    </div>
  );
}

export default Controls;
