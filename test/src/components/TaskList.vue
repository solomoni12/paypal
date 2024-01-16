<template>
  <div class="task-list">
    <h2>Task List</h2>
    <ul>
      <li v-for="task in tasks" :key="task.id" class="task-item">
        <div class="task-details">
          <span>{{ task.name }}</span>
          <span>${{ task.price }}</span>
        </div>
        <div class="task-actions">
          <button @click="initiatePayment(task)">Pay Now</button>
        </div>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const tasks = ref([]);

const initiatePayment = async (task) => {
  try {
    const response = await fetch(`http://127.0.0.1:8000/api/tasks/${task.id}/pay`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
    });

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const paymentData = await response.json();

    // Redirect the user to PayPal for payment
    window.location.href = paymentData.paypal_link;
  } catch (error) {
    console.error('Error initiating payment:', error);
  }
};

// Fetch tasks from the backend on component mount
onMounted(async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/tasks');

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    tasks.value = await response.json();
  } catch (error) {
    console.error('Error fetching tasks:', error);
  }
});
</script>

<style scoped>
.task-list {
  margin: 20px;
}

.task-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px;
  border: 1px solid #ccc;
  margin-bottom: 10px;
  list-style: none;
}

.task-details {
  display: flex;
  flex-grow: 1;
  justify-content: space-between;
  align-items: center;
}

.task-actions button {
  cursor: pointer;
  background-color: #4caf50;
  color: white;
  border: none;
  padding: 8px 16px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 14px;
  margin: 4px 2px;
  transition-duration: 0.4s;
  border-radius: 4px;
}

.task-actions button:hover {
  background-color: white;
  color: black;
  border: 1px solid #4caf50;
}
</style>
