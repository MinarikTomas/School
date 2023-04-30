# Assignment 2

### Description
Implement barber shop with waiting room. Use signalization and rendezvous.

To test our solution all you have to do is execute the main function.

### Class `shared`
Class used to share mutex, semaphores and waiting room count between threads.
#### Variables
- mutex - lock
- waiting room - waiting room count
- customer, barber - semaphores for rendezvou(meeting) before cutting hair
- customer_done, barber_done - semaphores for rendezvou(meeting) after cutting hair
### Functions `get_haircut`, `walk`, `growing_hair`
Functions for printing customer's state and also putting customer to sleep for x seconds.
### Function `cut_hair`
Function to print message when barber is cutting hair and also puttin barber to sleep.
### Function `customer`
Function to simulate customer 
#### Variables
- shared - shared class object
- i - thread id

1. Check current number of customer in waiting room. if waiting room is full walk away. Else increase waiting room count with locked mutex and start process of cutting hair
2. Send customer signal and wait for barber signal (first rendezvou)
3. call `get_haircut`
4. send customer_done signal and wait for barber_done signal (second rendezvou)
5. Decrease waiting room count with locked mutex
6. Call `growing_hair`
7. Repeat process

### Function `barber`
Function to simulate barber.
#### Variables
- shared - shared class object

1. Barber sends signal and waits for customer (first rendezvou)
2. Call `cut_hair`
3. Send barber_done signal and wait for customer_done signal (second rendezvou)
4. Repeat process

### Main
Creates shared class object, threads for customers and thread for barber. Number of customers is determined by global variable NUM_CUSTOMERS. Waiting room size is determined by global variable WAITING_ROOM_SIZE.

### Example output
Output for 5 customers and waiting room size 3.
![alt text](https://github.com/TomasMinarik/Minarik_104538_feippds/blob/02/ppds2_example.png "Example output")
