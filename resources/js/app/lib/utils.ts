export const formatCurrency = (amount: number) => {
  //TODO fix this
  return (amount).toLocaleString('en-US', {
    style: 'currency',
    currency: 'USD',
  });
};

export const calculatePercentage = (amount: number, total: number) => {
  return Math.round((amount / total) * 100);
}

export function debounce<T extends (...args: any[]) => any>(callback: T, delay: number) {
  let timeoutId: NodeJS.Timeout;

  return function (this: ThisParameterType<T>, ...args: Parameters<T>) {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => {
      callback.apply(this, args);
    }, delay);
  };
}