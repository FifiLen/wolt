const { test, expect } = require('@playwright/test');

const baseURL = 'http://127.0.0.1:8000/api'; // Update with your API base URL

function getRandomNumber(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

let authToken = '';
let orderId = '';
let restaurantId = '677993288fbedfb0b45059fd'; // Provided restaurant ID
const randomNumber = getRandomNumber(1, 100);
const email = `john.doe${randomNumber}@example.com`;

// Workflow Test: Register, Login, Create Order, Update Order, Cancel Order
test('Complete order workflow', async ({ request }) => {
    // 1. Register a new user
    const registerResponse = await request.post(`${baseURL}/register`, {
        data: {
            name: 'John Doe',
            email: email,
            password: 'password123',
            password_confirmation: 'password123',
        },
    });

    expect(registerResponse.status()).toBe(200);
    console.log('User registered:', await registerResponse.json());

    // 2. Login with the registered user
    const loginResponse = await request.post(`${baseURL}/login`, {
        data: {
            email: email,
            password: 'password123',
        },
    });

    expect(loginResponse.status()).toBe(200);
    const loginData = await loginResponse.json();
    authToken = loginData.token;
    console.log('Login successful, token:', authToken);

    // 3. Get restaurant menu
    const menuResponse = await request.get(`${baseURL}/restaurants/${restaurantId}/menu`);
    expect(menuResponse.status()).toBe(200);
    const menu = await menuResponse.json();
    console.log('Menu fetched:', menu);

    const firstMenuItem = menu.menu[0]?.items[0]; // Get the first item from the first menu section
    if (!firstMenuItem) {
        throw new Error('No menu items available for ordering.');
    }
    const menuItemName = firstMenuItem.name;
    console.log('Selected menu item:', menuItemName);

    // 4. Create an order
    const createOrderResponse = await request.post(`${baseURL}/orders`, {
        headers: {
            Authorization: `Bearer ${authToken}`,
        },
        data: {
            session_id: 'user-session-12345',
            restaurant_id: restaurantId,
            items: [
                { menu_item_id: menuItemName, quantity: 2 },
            ],
        },
    });

    expect(createOrderResponse.status()).toBe(201);
    const order = await createOrderResponse.json();
    orderId = order._id; // Capture order ID for further actions
    console.log('Order created:', order);

    // 5. Get order details
    const getOrderResponse = await request.get(`${baseURL}/orders/${orderId}`, {
        headers: {
            Authorization: `Bearer ${authToken}`,
        },
    });

    expect(getOrderResponse.status()).toBe(200);
    const orderDetails = await getOrderResponse.json();
    expect(orderDetails._id).toBe(orderId);
    console.log('Order details:', orderDetails);
    console.log('Order ID ', orderId);

    // 6. Update order status
    const updateOrderResponse = await request.patch(`${baseURL}/orders/${orderId}/status`, {
        headers: {
            Authorization: `Bearer ${authToken}`,
        },
        data: {
            status: 'confirmed',
        },
    });

    expect(updateOrderResponse.status()).toBe(200);
    const updateResponse = await updateOrderResponse.json();
    console.log('Order status updated:', updateResponse);

    // 7. Cancel the order
    const cancelOrderResponse = await request.delete(`${baseURL}/orders/${orderId}`, {
        headers: {
            Authorization: `Bearer ${authToken}`,
        },
    });

    expect(cancelOrderResponse.status()).toBe(200);
    const cancelResponse = await cancelOrderResponse.json();
    console.log('Order cancelled:', cancelResponse);
});
