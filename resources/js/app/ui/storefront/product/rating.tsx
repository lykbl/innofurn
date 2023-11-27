import Link from "@/app/ui/common/link";
import { TiStarHalfOutline } from "react-icons/ti";

const Rating = () => {
  return (<div>
    <div className='flex items-center'>
      <div className='mr-2 flex'>
        {/*//TODO fix stars*/}
        {Array(5).fill(null).map((item, index) => <TiStarHalfOutline key={index} size={24} />)}
      </div>
      <Link href='/reviews' className='hover:text-blue-500 hover:underline'>(299 reviews)</Link>
    </div>
  </div>
  );
}

export default Rating
