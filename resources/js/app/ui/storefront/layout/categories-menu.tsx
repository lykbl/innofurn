'use client';

import { Button } from '@/app/ui/common/button';
import { useKey, useToggle } from 'react-use';
import { BiMenu } from 'react-icons/bi';
import { IoIosArrowForward } from 'react-icons/io';
import clsx from 'clsx';
import Accordion from '@/app/ui/animated/accordion';
import { AnimatePresence, motion } from 'framer-motion';

const categories = [
  'Shop By Department', 'Programs & Promos',
  // ...Array(5).fill(null).map((_, index) => 'Test_' + index)
];
const subcategories = [
  'Beds', 'Chairs', 'Tables',
  // ...Array(20).fill('Accordion')
];

enum VARIANT_NAMES {
  OPEN = 'open',
  CLOSED = 'closed',
}

function SubcategoryButton({ subheader }: { subheader: string }) {
  return (
    <Button className="flex w-full justify-between px-4 py-2" onClick={() => {}}>
      <h4>{subheader}</h4>
      <IoIosArrowForward size={24}/>
    </Button>
  )
}

export default function CategoriesMenu() {
  const [isOpen, toggle] = useToggle(false);
  useKey('Escape', () => toggle(false))

  return (
    <>
      <Button className="flex items-center" onClick={toggle}>
        <BiMenu size={24}/>
      </Button>
      <AnimatePresence>
        {
          isOpen && <motion.aside
            animate={isOpen ? VARIANT_NAMES.OPEN : VARIANT_NAMES.CLOSED}
            className='absolute left-0 top-0 flex w-full min-h-full'
          >
            <motion.div
              initial={VARIANT_NAMES.CLOSED}
              animate={VARIANT_NAMES.OPEN}
              exit={VARIANT_NAMES.CLOSED}
              variants={{
                [VARIANT_NAMES.OPEN]: { transform: 'translateX(0%)' },
                [VARIANT_NAMES.CLOSED]: { transform: 'translateX(-100%)' },
              }}
              className="bg-gray-50 w-2/12 min-h-screen h-full relative z-[1]"
            >
              {categories.map(header =>
                <div key={header} className="border-b-2 py-4">
                  <h3 className="font-bold px-4 py-2">{header}</h3>
                  {subcategories.map((subheader, index) =>
                    index < 3 &&
                    <SubcategoryButton key={subheader} subheader={subheader} />
                  )}
                  {
                    subcategories.length > 3 &&
                    <Accordion>
                      <Accordion.Toggle
                        className='flex w-full justify-between px-4 py-2 bg-gray-50 rounded hover:outline'
                        openClassName='outline sticky top-0 z-[1]'
                      >
                        <h4 className="mr-2">See All</h4>
                      </Accordion.Toggle>
                      <Accordion.Content>
                        {subcategories.slice(3).map(subheader =>
                          <SubcategoryButton key={subheader} subheader={subheader} />
                        )}
                      </Accordion.Content>
                    </Accordion>
                  }
                </div>
              )
              }
            </motion.div>
            <motion.div
              initial={VARIANT_NAMES.CLOSED}
              animate={VARIANT_NAMES.OPEN}
              exit={VARIANT_NAMES.CLOSED}
              variants={{
                [VARIANT_NAMES.OPEN]: { background: 'rgba(0,0,0,0.5)' },
                [VARIANT_NAMES.CLOSED]: { background: 'rgba(0,0,0,0.0)' },
              }}
              className={
                clsx(
                  'cursor-pointer w-full h-full left-0 top-0 fixed',
                )}
              onClick={toggle}
            />
          </motion.aside>
        }
      </AnimatePresence>
    </>
  );
}
