export const numberFormat = (num: number) => {
 return num.toLocaleString("es-CO", {
  minimumFractionDigits: 0,
  maximumFractionDigits: 0,
  useGrouping: true,
}).replace(',', '.');
}

