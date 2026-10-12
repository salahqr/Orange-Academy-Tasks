function getProperties(obj) {
    return Object.keys(obj);
}
console.log(getProperties({ name: "Salah", age: 22 }));

function countProperties(obj) {
    return Object.keys(obj).length;
}
console.log(countProperties({ name: "Salah", age: 22 }));

function combineObjects(obj1, obj2) {
    let newObj = Object.assign({}, obj1, obj2);
    return newObj;
}
console.log(combineObjects(
    { name: "Salah" },
    { age: 22 }
));

function uppercaseValues(obj) {
    let newObj = {};

    for (let key of Object.keys(obj)) {
        newObj[key] = obj[key].toUpperCase();
    }

    return newObj;
}
console.log(uppercaseValues({
    name: "salah",
    city: "amman"
}));

function removeNull(obj) {
    let newObj = {};

    for (let key of Object.keys(obj)) {
        if (obj[key] !== null) {
            newObj[key] = obj[key];
        }
    }

    return newObj;
}
console.log(removeNull({
    name: "Salah",
    age: null,
    city: "Amman"
}));

function sortProperties(obj) {
    return Object.keys(obj).sort();
}
console.log(sortProperties({
    zebra: 1,
    apple: 2,
    orange: 3
}));
