import { IoIosArrowForward } from "react-icons/io";
import Link from "@/app/ui/common/link";

const path = [
  {
    'name': 'Back To Results',
    'href': '/',
  },
  {
    'name': 'Rugs',
    'href': '/cat/rugs',
  },
  {
    'name': 'Area Rugs',
    'href': '/cat/area-rugs',
  },
  {
    'name': 'SKU: W002352606',
    'href': '/product/w002352606',
  }
];

const Breadcrumb = () => {
  return (
    <div className='flex py-2'>
      {path.map(({name, href}, index) =>
        <Link key={name} className='flex items-center mr-2 hover:text-blue-500 hover:underline' href={href}>
          <p className='mr-2'>{name}</p>
          { index < path.length - 1 && <IoIosArrowForward size={16} /> }
        </Link>
      )}
    </div>
  )
}

export default Breadcrumb;
