import {
  createContext, FC, MouseEventHandler,
  ReactNode,
  useContext,
  useEffect,
  useRef,
} from 'react';
import { useToggle } from 'react-use';
import { IoIosArrowDown } from 'react-icons/io';
import { AnimatePresence, motion } from 'framer-motion';
import clsx from 'clsx';

enum VARIANT_NAMES {
  OPEN = 'open',
  CLOSED = 'closed',
}
interface ContextDefaults {
  isOpen: boolean,
  toggle: MouseEventHandler,
}

interface AccordionProps {
  children: ReactNode;
  className?: string;
}

interface ToggleProps  {
  children: ReactNode,
  className?: string,
  openClassName?: string,
}

interface ContentProps {
  children: ReactNode | ReactNode[],
  className?: string,
}

interface AccordionComponent extends FC<AccordionProps> {
  Toggle: FC<ToggleProps>
  Content: FC<ContentProps>
}

const AccordionContext = createContext<ContextDefaults>({
  isOpen: false,
  toggle: () => null,
});

const Accordion: AccordionComponent = ({ children }: AccordionProps): ReactNode => {
  const [isOpen, toggle] = useToggle(false);

  return (
    <AccordionContext.Provider value={{ isOpen, toggle }}>
      <motion.div
        initial={false}
        animate={isOpen ? VARIANT_NAMES.OPEN : VARIANT_NAMES.CLOSED}
      >
        {children}
      </motion.div>
    </AccordionContext.Provider>
  );
}

const Toggle: FC<ToggleProps> = ({ children, className, openClassName }: ToggleProps): ReactNode => {
  const { isOpen, toggle } = useContext(AccordionContext);
  const toggleRef = useRef<HTMLButtonElement|null>(null);

  const a: number = 1;
  const b: number = 1;
  useEffect(() => {
    if (!isOpen) {
      toggleRef.current?.scrollTo(a, b);
    }
  }, [isOpen]);

  return (
    <motion.button
      onClick={toggle}
      className={clsx(
        className,
        isOpen && openClassName,
      )}
      ref={toggleRef}
    >
      {children}
      <motion.div
        variants={{
          [VARIANT_NAMES.OPEN]: { rotate: 180 },
          [VARIANT_NAMES.CLOSED]: { rotate: 0 }
        }}
        transition={{ duration: 0.2 }}
        style={{ originY: 0.55 }}
      >
        <IoIosArrowDown
          size={24}
        />
      </motion.div>
    </motion.button>
  );
}

function Content({ children, className }: ContentProps): ReactNode {
  const { isOpen } = useContext(AccordionContext);
  const manyChildren = children instanceof Array;

  return <AnimatePresence>
    {isOpen && (
      <motion.section
        className={className}
        initial={VARIANT_NAMES.CLOSED}
        exit={VARIANT_NAMES.CLOSED}
        variants={{
          [VARIANT_NAMES.OPEN]: {
            transition: {
              type: "spring",
              staggerChildren: 0.015
            }
          },
        }}
      >
        {
          manyChildren
            ? children?.map((child: ReactNode, index: number) =>
            <motion.div
              key={index}
              variants={{
                [VARIANT_NAMES.OPEN]: {
                  opacity: 1,
                  transition: { type: "spring"},
                },
                [VARIANT_NAMES.CLOSED]: {
                  opacity: 0,
                  transition: { duration: 0.3 },
                }
              }}
            >
              {child}
            </motion.div>
           ) : children
        }
      </motion.section>
    )}
  </AnimatePresence>;
}

Accordion.Toggle = Toggle;
Accordion.Content = Content;

export default Accordion;
