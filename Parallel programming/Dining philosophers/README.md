# Assignment 3

### Description 
Solve dining philosophers problem using left/right handed philosophers or with token.

We decided to use left/right handed philosophers implementation. Where one philosopher is right-handed and others are left-handed.
To test out solution just execute the main function.

### Class `shared`
Class used to share forks between threads.
#### Variables
- forks - List of forks. Each fork is represented as mutex.

### Functions `eat`, `think`
Function to print current state of philosopher with philosopher's id
#### Variables 
- i - philosopher's id

### Function `philosopher`
Function to simulate philosopher.
#### Variables 
- i - philosopher's id
- shared - shared class object

1. Call `think`
2. Check philosopher's id. if id is 0 go for the right fork first, else go for the left one.
3. Pick up the first fork(lock mutex).
4. Pick up the second fork(lock mutex).
5. Call `eat`
6. Put down the first fork(unlock mutex).
7. Put down the second fork(unlock mutex).
8. Repeat process for NUM_RUNS times.
### Function `main`
Create shared class object and create philosophers. Number of philosophers depends on the NUM_PHILOSOPHERS variable.
### Deadlock
Because of the one right-handed philosopher it is not possible to get into a situation where every philosopher holds one fork. In our case right-handed philosopher is philosopher with id 0. If every left-handed philosopher picked up the first fork. The one right-handed philosopher would not be able to pick up his first fork because philosopher 4 already picked it up. Fork on the left side of philosopher 0(his second fork, fork 0) is free and can be picked up by philosopher 1. Philosopher 1 can start eating after that.
### Comparison with the waiter solution
#### Waiter solution
Waiter(semaphore) lets only p-1 philosophers pick up forks, where p is number of philosophers. 
#### Starvation
In both solutiuons eating depends on thread switiching. It is possible for philosopher to starve just because of thread switching.
