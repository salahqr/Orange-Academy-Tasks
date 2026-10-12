export function calculateAverage(grades) {
    const total = grades.reduce((sum, grade) => sum + grade, 0);

    return total / grades.length;
}


export function getStatus(average) {
    return average >= 50 ? "Pass" : "Fail";
}