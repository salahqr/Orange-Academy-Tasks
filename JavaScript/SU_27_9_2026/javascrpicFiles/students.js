const students = [
    {
        id: 1,
        name: "Ahmad",
        grades: [80, 90, 75]
    },
    {
        id: 2,
        name: "Sara",
        grades: [95, 88, 92]
    },
    {
        id: 3,
        name: "Omar",
        grades: [60, 70, 65]
    },
    {
        id: 4,
        name: "Lina",
        grades: [45, 50, 40]
    },
    {
        id: 5,
        name: "Ali",
        grades: [85, 78, 90]
    }
];


// Named export
export function getStudents() {
    return students;
}


// Named export
export function getStudentById(id) {
    return students.find(student => student.id === id);
}


// Default export
export default students;