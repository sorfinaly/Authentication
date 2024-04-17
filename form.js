
// filename = form.js

document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".form__input, .form__textarea").forEach(function(input) {
        const errorMessage = input.nextElementSibling; // Select the next sibling element (error message) of the input

        input.addEventListener("input", function() {
            const pattern = input.getAttribute("pattern"); // Get the pattern attribute value of the input (if applicable)
            const inputValue = input.value; // Get the value entered by the user
            
            if (pattern && !new RegExp(pattern).test(inputValue)) {
                input.classList.add("form__input--error"); // Add error class to input
                errorMessage.style.visibility = "visible"; // Show error message
            } else {
                input.classList.remove("form__input--error"); // Remove error class from input
                errorMessage.style.visibility = "hidden"; // Hide error message
            }
        });
    });

    function fetchStudentData() {
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get('id');

        fetch(`form.php?id=${id}`)
            .then(response => response.json()) // Assuming the response is in JSON format
            .then(data => {
                // Process the fetched data
                // You can manipulate the DOM to display the data as required
                const studentDetailsContainer = document.getElementById('student-detailss');
    
                // Clear previous content
                studentDetailsContainer.innerHTML = '';
    
                // Create a container for student details
                const detailsContainer = document.createElement('div');
                detailsContainer.classList.add('detailss-container');
    
                // Loop through the fetched data and create detail items
                data.forEach(student => {
                    const detailItem = document.createElement('div');
                    detailItem.classList.add('detail-item');
                    detailItem.innerHTML = `
                                <table>
                                    <tr>
                                        <td class="detail-label">Name:</td>
                                        <td class="detail-value">${student.name}</td>
                                    </tr>
                                    <tr>
                                        <td class="detail-label">Matric No:</td>
                                        <td class="detail-value">${student.matricno}</td>
                                    </tr>
                                    <tr>
                                        <td class="detail-label">Email:</td>
                                        <td class="detail-value">${student.email}</td>
                                    </tr>
                                    <tr>
                                        <td class="detail-label">Current Address:</td>
                                        <td class="detail-value">${student.curraddress}</td>
                                    </tr>
                                    <tr>
                                        <td class="detail-label">Home Address:</td>
                                        <td class="detail-value">${student.homeaddress}</td>
                                    </tr>
                                    <tr>
                                        <td class="detail-label">Mobile Phone:</td>
                                        <td class="detail-value">${student.mobilephone}</td>
                                    </tr>
                                    <tr>
                                        <td class="detail-label">Home Phone:</td>
                                        <td class="detail-value">${student.homephone}</td>
                                    </tr>
                                </table>
                            `;

                    detailsContainer.appendChild(detailItem);
                });
    
                // Append the details container to the student details container
                studentDetailsContainer.appendChild(detailsContainer);
            })
            .catch(error => console.error('Error fetching student data:', error));
    }
    

    // Call the fetchStudentData function when the page loads
    fetchStudentData();

    // Function to handle back button click event
    function back() {
        window.location.href = "index.html";
    }

    // Event listener for back button click
    document.getElementById('back-btn').addEventListener('click', back);
});

