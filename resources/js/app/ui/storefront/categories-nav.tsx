import { Category } from '@/app/lib/definitions';

const categories: Category[] = [
  {
    id: 1,
    name: 'Furniture',
    link: '/cat/furniture',
  },
  {
    id: 2,
    'name': 'Kitchen',
    'link': '/cat/kitchen',
  },
  {
    id: 3,
    'name': 'Rugs',
    'link': '/cat/rugs',
  }
];

export default function CategoriesNav() {
  return <nav className='flex gap-2 py-2 justify-center'>
    {
      categories.map(category =>
        <div key={category.id}>
          <button className='rounded font-medium text-black hover:bg-gray-100 p-1 hover:underline hover:text-blue-600 focus:outline-blue-700'>
            {category.name}
          </button>
        </div>
      )
    }
  </nav>;
}
