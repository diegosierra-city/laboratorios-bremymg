export const numberFormat = (num: number) => {

 var formattedNumber = num.toLocaleString("es-CO", {
  //notation: "compact",
  //compactDisplay: "short",
 // style: "currency", // simbolo y decimales
  currency: "COP",
  //currencySign: "accounting",
 });

 return formattedNumber;
}

