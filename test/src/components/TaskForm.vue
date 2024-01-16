<template>
  <div>
    <h2>Add Task</h2>
    <form @submit.prevent="handleTaskSubmit">
      <label>
        Task Name:
        <input v-model="taskName" type="text" required />
      </label>
      <label>
        Task Price:
        <input v-model="taskPrice" type="number" required />
      </label>
      <button type="submit" :disabled="paymentCompleted || submitting">
        {{ submitting ? "Adding Task..." : "Add Task with Payment" }}
      </button>
      <PayPalButton v-if="paymentCompleted" :amount="taskPrice" @payment-success="onPaymentSuccess" />
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import PayPalButton from 'vue-paypal-checkout';

const taskName = ref('');
const taskPrice = ref('');
const paymentCompleted = ref(false);
const submitting = ref(false);

const handleTaskSubmit = async () => {
    try {
        submitting.value = true;

        // Step 1: Send task details to the backend
        const taskResponse = await fetch('http://127.0.0.1:8000/api/tasks', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                name: taskName.value,
                price: taskPrice.value,
            }),
        });

        if (!taskResponse.ok) {
            throw new Error(`HTTP error! Status: ${taskResponse.status}`);
        }

        const task = await taskResponse.json();

        // Step 2: Redirect user to the PayPal payment page
        const paymentResponse = await fetch(`http://127.0.0.1:8000/api/tasks/${task.id}/pay`, {
            method: 'GET',
        });

        if (!paymentResponse.ok) {
            throw new Error(`HTTP error! Status: ${paymentResponse.status}`);
        }

        const paymentData = await paymentResponse.json();

        // Redirect the user to PayPal for payment
        window.location.href = paymentData.paypal_link;

    } catch (error) {
        console.error('Error:', error);

    } finally {
        submitting.value = false;
    }
};


const onPaymentSuccess = () => {
  // Handle payment success logic here, e.g., update task status
  console.log('Payment Successful');
  paymentCompleted.value = true;
};
</script>
