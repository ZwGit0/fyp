# WiseShopperAI

## Overview
WiseShopperAI is a web-based intelligent shopping platform developed as a Final Year Project (FYP). The system combines e-commerce functionality, personalized product recommendations, and an AI-powered chatbot to enhance the user shopping experience and improve decision-making.

The platform is built using **Laravel** for the backend and **Blade** for the frontend, with **Filament Admin** used for administrative and management tasks. It integrates **Botpress Cloud** to provide conversational AI capabilities and supports role-based access for **Admins, Sellers, and Users**.

---

## Core Objectives
- Provide a smart shopping platform with AI-assisted chatbot
- Deliver personalized product recommendations based on user behavior  
- Enable conversational shopping assistance via chatbot  
- Support scalable product, category, and seller management  
- Separate concerns between admin, seller, and customer roles  

---

## Key Features

### 1. User Features
- User registration and authentication  
- Product browsing by category and type  
- Add-to-cart and checkout workflow  
- Delivery or pickup selection  
- Address and payment method selection  
- AI chatbot for product inquiries and shopping assistance  
- Personalized product recommendations based on frequently added-to-cart items and frequently purchased items

### 2. Seller Features
- Dedicated seller login  
- Seller dashboard (custom Laravel views)  
- CRUD operations for seller-owned products only  
- Restriction preventing sellers from modifying other sellers’ products  

### 3. Admin Features (Filament Admin)
- Admin authentication  
- Full CRUD access to all products  
- Category and product type management  
- Chatbot intent and response management  
- System-wide monitoring and management  

### 4. AI & Recommendation System
- Botpress Cloud integration for conversational AI  
- Custom intents managed via Laravel and Filament  
- Auto-sync of intents to Botpress NLU API  
- Knowledge based learning for chatbot interaction

---

## Setup / Build Instructions
1. Ensure **PHP 8+**, **Composer**, and **Laravel 10+** are installed  
2. Clone the repository:  
```bash
git clone <https://github.com/ZwGit0/fyp>
cd WiseShopperAI
