import Input from "@/app/ui/common/input";

const offersData = [
  {
    title: '5 Year Protection Plan For $9.29',
    details: 'Popup whatever blah blah blah',
  }
];
const ExtraOffers = () => {
  return (
    <div className='w-full bg-blue-100 p-4 rounded'>
      <h3 className='text-2xl'>What We Offer</h3>
      <div>
        {offersData.map(({ title, details }, index) =>
          <div key={index} className='flex rounded'>
            <div className='flex items-center mr-4'>
              <Input className='bg-white rounded border border-solid border-black cursor-pointer hover:bg-neutral-200' type='checkbox' />
            </div>
            <div className='flex flex-col items-start'>
              <h4 className='text-xl'>{title}</h4>
              <div>
                <button className='text-blue-600 underline hover:text-blue-700'>
                  {'What\'s Covered'}
                </button>
                <div>
                  {details}
                </div>
              </div>
            </div>
          </div>
        )}
      </div>
    </div>
  );
};

export default ExtraOffers;
