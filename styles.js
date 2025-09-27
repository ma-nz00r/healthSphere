// Disease data with name, description, and image URLs
const diseases = [
    {
        name: "Asthma",
        description: "Asthma is a condition in which your airways narrow and swell, producing extra mucus, making breathing difficult.",
        image: "https://via.placeholder.com/300x300?text=Asthma+Image"
    },
    {
        name: "Arthritis",
        description: "Arthritis is an inflammation of one or more joints, causing pain, swelling, stiffness, and limited movement.",
        image: "https://via.placeholder.com/300x300?text=Arthritis+Image"
    },
    {
        name: "AIDS",
        description: "AIDS (Acquired Immunodeficiency Syndrome) is a disease caused by HIV, leading to a weakened immune system.",
        image: "https://via.placeholder.com/300x300?text=AIDS+Image"
    },
    {
        name: "Alzheimer's Disease",
        description: "Alzheimer's Disease is a progressive neurological disorder that causes memory loss, confusion, and changes in behavior.",
        image: "https://via.placeholder.com/300x300?text=Alzheimer%27s+Image"
    },
    {
        name: "Anemia",
        description: "Anemia is a condition in which you lack enough healthy red blood cells to carry adequate oxygen to your body's tissues.",
        image: "https://via.placeholder.com/300x300?text=Anemia+Image"
    },
    {
        name: "Angina",
        description: "Angina is chest pain or discomfort caused when your heart muscle doesn’t get enough oxygen-rich blood.",
        image: "https://via.placeholder.com/300x300?text=Angina+Image"
    },
    {
        name: "Acne",
        description: "Acne is a skin condition that occurs when hair follicles are clogged with oil and dead skin cells, leading to pimples or cysts.",
        image: "https://via.placeholder.com/300x300?text=Acne+Image"
    },
    {
        name: "Appendicitis",
        description: "Appendicitis is an inflammation of the appendix, a small tube connected to your large intestine, which may lead to severe pain and infection.",
        image: "https://via.placeholder.com/300x300?text=Appendicitis+Image"
    },
    {
        name: "Alopecia",
        description: "Alopecia is an autoimmune disorder that causes hair loss on the scalp or other parts of the body.",
        image: "https://via.placeholder.com/300x300?text=Alopecia+Image"
    }
];

// Function to populate the disease list in the UI
function populateDiseaseList() {
    const diseaseListContainer = document.getElementById('disease-list');
    diseases.forEach((disease, index) => {
        const listItem = document.createElement('li');
        const button = document.createElement('button');
        button.textContent = disease.name;
        button.onclick = () => showDiseaseDetails(index);
        listItem.appendChild(button);
        diseaseListContainer.appendChild(listItem);
    });
}

// Function to display the selected disease details
function showDiseaseDetails(index) {
    const disease = diseases[index];
    
    // Set the disease details section
    document.getElementById('disease-name').textContent = disease.name;
    document.getElementById('disease-description').textContent = disease.description;
    document.getElementById('disease-image').src = disease.image;
}

// Initialize the page with disease list
populateDiseaseList();
