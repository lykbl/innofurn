import {
  ReactNode,
  useState,
  useCallback, useEffect, useRef
} from "react";
import { motion } from "framer-motion";
import clsx from "clsx";
import Items from "@/app/ui/animated/carousel/items";
import Controls from "@/app/ui/animated/carousel/controls";
import { debounce } from "@/app/lib/utils";

const carouselVariants = {
  scrolled: (offset: number) => ({
    x: `-${offset}px`,
  }),
};
const calculateMaxWidth = (el: HTMLDivElement) => parseFloat(window.getComputedStyle(el).width);
const calculateGap = (
  size: number,
  maxWidth: number,
  itemWidth: number
) => (maxWidth - (size * itemWidth)) / (size - 1);
const calculateScroll = (currentIndex: number, gap: number, itemWidth: number) => currentIndex * (itemWidth + gap);
const calculateMaxItems = (maxWidth: number, itemWidth: number) => Math.floor(maxWidth / itemWidth);
interface CarouselProps {
  className?: string;
  items: ReactNode[];
  itemsPerPage?: number;
  controlsSize?: number;
}

const Carousel = ({ items, className, itemsPerPage = 0, controlsSize = 24 }: CarouselProps) => {
  const [currentIndex, setCurrentIndex] = useState<number>(0);
  const [itemWidth, setItemWidth] = useState<number>(0);
  const [maxWidth, setMaxWidth] = useState<number>(0);
  const childRef = useCallback((node: HTMLDivElement) => {
    if (!node) return;

    setItemWidth(node.clientWidth);
  }, []);
  const parentRef = useRef<HTMLDivElement>(null);

  const maxItems = calculateMaxItems(maxWidth, itemWidth);
  if (!itemsPerPage || itemsPerPage > maxItems) {
    itemsPerPage = maxItems
  }
  const gap = calculateGap(itemsPerPage, maxWidth, itemWidth);
  const scrollOffset = calculateScroll(currentIndex, gap, itemWidth);

  const next = () => {
    setCurrentIndex((prevIndex) =>
      prevIndex + itemsPerPage < items.length ? prevIndex + 1 : prevIndex
    );
  };
  const prev = () => {
    setCurrentIndex((prevIndex) =>
      prevIndex && prevIndex - 1
    );
  };

  useEffect(() => {
    if (!parentRef.current) return;

    const resizeObserver = new ResizeObserver(debounce((entries: ResizeObserverEntry[]) => {
      const parentWrapper = entries[0].target as HTMLDivElement;
      setMaxWidth(calculateMaxWidth(parentWrapper));
    }, 500));

    resizeObserver.observe(parentRef.current);

    return () => resizeObserver.disconnect();
  }, [parentRef]);

  return (
    <div
      className='overflow-x-hidden relative'
      ref={parentRef}
    >
      <motion.div
        className={clsx('flex', className, {
          'opacity-0': !maxWidth,
        })}
        custom={scrollOffset}
        animate='scrolled'
        variants={carouselVariants}
      >
        <Items
          items={items}
          childRef={childRef}
          gap={gap}
          itemsCount={items.length}
        />
      </motion.div>
      <Controls
        prev={prev}
        next={next}
        currentIndex={currentIndex}
        itemsPerPage={itemsPerPage}
        itemsCount={items.length}
        controlsSize={controlsSize}
      />
    </div>
  );
};

Carousel.Controls = Controls;
Carousel.Items = Items;

export default Carousel;
