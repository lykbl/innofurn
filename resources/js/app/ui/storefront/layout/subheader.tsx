import { Button } from '@/app/ui/common/button';
import CategoriesMenu from '@/app/ui/storefront/layout/categories-menu';
import {ReactNode} from "react";

const promotions = [
  {
    id: 1,
    name: 'Ongoing Promotions',
    link: '/cat/furniture',
  },
  {
    id: 2,
    'name': 'Work With Us',
    'link': '/cat/kitchen',
  },
  {
    id: 3,
    'name': 'Room Planner',
    'link': '/room-planner',
  }
];

export default function Subheader(): ReactNode {
  return (
    <div className='border-b-2'>
      <div className='flex gap-2 py-2 px-4 max-w-screen-2xl mx-auto justify-between'>
        <div className='flex items-center'>
          <CategoriesMenu />
          {promotions.map(promotion =>
            <div key={promotion.id}>
              <Button className='font-medium mx-2'>
                {promotion.name}
              </Button>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
