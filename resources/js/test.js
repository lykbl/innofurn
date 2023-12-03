async function main() {
  const test = 'abc';

  const componentCallback = async (value) => {
    return value;
  }

  const componentOuterCallback = async (callback) => {
    return await (async (originalValue) => {
      return originalValue + 1;
    })();
  }

  const first = componentCallback(4);
  const result = await componentOuterCallback(componentCallback);
  const final = await result(1);

  return result;
}

console.log(main());
