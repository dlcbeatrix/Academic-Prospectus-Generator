# 🎓 Laureandosi 2.0 - Academic Prospectus Generator

![Project Date](https://img.shields.io/badge/date-April%202%2C%202024-blue)
![Project Status](https://img.shields.io/badge/status-completed-brightgreen)
![Tech Stack](https://img.shields.io/badge/PHP-7.4+-777BB4?logo=php&logoColor=white)
![Tech Stack](https://img.shields.io/badge/JSON-Data-000000?logo=json&logoColor=white)
![Course](https://img.shields.io/badge/course-Software%20Engineering-red)

**Laureandosi 2.0** is an automated software system designed for the **University of Pisa** to manage and generate official graduation prospectuses. 
Developed as the final project for the **Software Engineering course** (A.A. 2023-2024), it focuses on formalizing administrative workflows through rigorous engineering methodologies.

---

## 📋 Project Overview
The system automates the preparation of graduation documents for the University's Didactic Unit. It processes raw student career JSON data to generate formal PDF reports for both graduation committees and candidates, ensuring mathematical precision in grade simulations.

### Key Features:
* **Automated PDF Generation:** High-fidelity official reports created via the **FPDF** library.
* **Advanced Career Filtering:** Automatically identifies and excludes extracurricular exams from weighted average calculations.
* **Specialized IT Engineering Logic:** Implements the specific "bonus" rule for Computer Engineering students (removal of the lowest grade for on-time graduates).
* **Institutional Email Integration:** Secure, asynchronous delivery of prospectuses using **PHPMailer**.
* **Administrative Configurability:** Full control over degree formulas and parameters via external **JSON configuration files**.

---

## 🛠️ Technical Workflow & Tools

### Engineering Methodology
This project follows a complete Software Engineering lifecycle:
* **Requirements Analysis:** 25 functional and 5 non-functional requirements tracked via a **Traceability Matrix**.
* **Architectural Design:** Modeled using **OOP principles**, CRC cards, and UML 2.0 diagrams (Class, Sequence, and Deployment).
* **Software Design:** Implemented as a modular system with specialized classes for career management and GUI orchestration.

### Development Stack
* **IDE:** ![PhpStorm](https://img.shields.io/badge/PhpStorm-000000?style=flat&logo=phpstorm&logoColor=white) used for advanced code management and debugging.
* **Modeling:** ![Visual Paradigm](https://img.shields.io/badge/Visual%20Paradigm-231F20?style=flat&logo=visual-paradigm&logoColor=white) for formal UML modeling and requirement tracking.
* **Environment:** ![WordPress](https://img.shields.io/badge/WordPress-21759B?style=flat&logo=wordpress&logoColor=white) and **Local by Flywheel** for template management and local server orchestration.

---

## 🧪 Automated Testing & QA
To ensure the reliability of academic calculations, the system includes a custom **automated test suite**:
* **Data-Driven Validation:** Career logic is verified against a `dati_test.json` file containing complex real-world scenarios.
* **Visual Reporting:** A dedicated testing interface provides real-time "Green/Red" feedback on weighted averages, credit totals, and specific bonus logic.

---

## 📸 Screenshots

### 🖥️ User Interface (GUI)
* **Main Interface**
  <img width="1920" height="1140" alt="Screenshot 2026-02-05 131740" src="https://github.com/user-attachments/assets/3cffed71-4b32-4b2c-9a54-9c6b0ac37d94" />
  
  *The intuitive management dashboard for the Didactic Unit*

### 🧪 Automated Testing Suite
* **Test result**
  <img width="1920" height="1140" alt="Screenshot 2026-02-05 131825" src="https://github.com/user-attachments/assets/372a436a-bd0a-4523-b261-035c80673501" />

  *Automated validation showing successful career and logic tests*

### 📄 Generated Prospectus
* **PDF Prospectus**
  <img width="1920" height="1140" alt="Screenshot 2026-02-05 131806" src="https://github.com/user-attachments/assets/cfa49158-2a62-4bcb-b707-ab55df56c554" />
  
  *Directory containing the generated graduation prospectuses.*


  <img width="808" height="849" alt="Screenshot 2026-02-05 132717" src="https://github.com/user-attachments/assets/5ad4d64b-aec0-42ef-ab28-72eaa091b99f" />
  
  *Sample PDF output including career simulation and graduation formulas*

---

## 🚀 Installation
1. Setup a local PHP/WordPress environment (recommended: **Local by Flywheel**).
2. Clone the repository into `wp-content/themes/twentytwentyfour/`.
3. Create a new WordPress page and assign the `template_laureandosi` template.
4. Add the slur `laureandosi-2-0`
5. Create another WordPress page and assign the `test_template` template
6. Add the slur `test`
7. Configure database parameters and graduation formulas in `src/file_configurazione/info-CdL.json`.

---

## 👤 Author
Developed by **dlcbeatrix**, *Computer Engineering Student*
