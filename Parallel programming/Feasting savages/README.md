# Assignment 4

### Description
Implement feasting savages problem with multiple cooks.
- Savages must wait for each other before taking a portion from the pot.
- If the pot is empty, call cooks.
- Savages wait until cooks fill the pot.
- When the pot is full savages continue feasting.

To test our solution all you have to do is execute the main function.

### Class `shared`
Class used to share data between threads.
#### Variables
- mutex - lock
- count - number of savages that are currently waiting.
- savage_event - event for 5th savage to send signal to other waiting savages so they can start feasting.
- servings - current number of servings in pot
- empty_pot - event for savage thread to wake up all cook threads
- full_pot - semaphore for last cook to signal that the pot is full
- cooking - bool for current state of cooks. True=cooking, False=not cooking
- cooking_event - event for cook to signal to savage threads that the cooking have ended.
- cooks_ready - event for cook to signal to savage threads that all cooks are ready.
- cooks_ready_count - current count of cooks that are ready.
Cooking_event first signal is sent in constructor because the first portions are already prepared.

### Functions `get_serving`, `cook_portion`
Functions for taking/adding a portion. While calling these functions the mutex must be locked.

### Function `savage`
Function that runs savage's code. 
1. Wait for cook threads to get ready.
2. Start while loop
3. Increase count for waiting savages. If the count is 5, send signal to other savage threads.
4. Check if the cooks are currently cooking. If they are wait for signal.
5. Check number of portions in the pot.
6. If pot is empty, send signal to cooks and wait for the pot to be full.
7. Call `get_serving`
8. Sleep for 5 seconds and repeat while loop.

### Functions `cook`
Function that runs cook's code.
1. Increase cook_ready_count. If the count equals to the number of cooks, send signal to savage threads.
2. Start while loop.
3. Wait for empty_pot signal and then call `cookr_portion`
4. When every cook added one portion send full_pot signal and cooking_event signal.
5. Repeat while loop.

### `main`
Creates shared object, savage threads and cook threads. Number of savage threads is determined by global variable N. Number of cook threads is determined by global variable COOKS. In our case N=5 and COOKS=5.

### Synchronization
1. ##### Savages waiting for cooks to get ready
This part is here because sometimes savage thread would send empty_pot signal but only 2 cook threads were "ready". This would end up with a deadlock. So we decided to add one event. When all cook threads are in the `cook` function then the last one sends signal to savage threads.
```python
#cook
shared.mutex.lock()
shared.cooks_ready_count += 1
if shared.cooks_ready_count == COOKS:
    shared.cooks_ready.signal()
shared.mutex.unlock()
    
#savage
shared.cooks_ready.wait()
```

2. ##### Savage threads waiting for each other
Savage threads wait at the savage_event.wait() until the 5th savage sends signal. After sending signal the last thread clears count and savage_event.
```python
#savage
shared.mutex.lock()
shared.count += 1
print(f'Savage {i} is waiting. Current count is {shared.count}.')
if shared.count == N:
    print('Everyone is here.\n------------')
    shared.count = 0
    shared.savage_event.signal()
    shared.savage_event.clear()
    shared.mutex.unlock()
else:
    shared.mutex.unlock()
    shared.savage_event.wait()
```

3. ##### If cooks are cooking
This part is here to stop multiple threads checking the number of portions in pot when pot is empty. First savage thread checks if cooking=True. If it is true that means the pot is empty and cooks are currently cooking. Savage threads then wait at cooking_event.wait() until last cook adds portion and sends signal.
```python
#savage
shared.mutex.lock()
if shared.cooking:
    shared.mutex.unlock()
    shared.cooking_event.wait()
    shared.mutex.lock() 
#cook
shared.mutex.lock()
cook_portion(cook_id, shared)
if shared.servings == COOKS:
    print('------------')
    shared.full_pot.signal()
    shared.cooking = False
    shared.cooking_event.signal()
shared.mutex.unlock()
```

4. ##### Calling cooks when pot is empty
When savage thread finds out that the pot is empty. First it sets cooking to true and clears cooking_event. Then unlocks mutex so cooks can use mutex. After that it sends empty_pot signal and clears empty_pot event.
```python
#savage
if shared.servings == 0:
    print(f'Savage {i}: Waking up cooks.\n------------')
    shared.cooking = True
    shared.cooking_event.clear()
    shared.mutex.unlock()
    shared.empty_pot.signal()
    shared.empty_pot.clear()
#cook
shared.empty_pot.wait()
```

5. ##### Cooks finished cooking
When last cook thread adds portion to the pot. First it sends full_pot signal. Then it sets cooking to false and sends cooking_event signal.
```python
#cook
cook_portion(cook_id, shared)
if shared.servings == COOKS:
    print('------------')
    shared.full_pot.signal()
    shared.cooking = False
    shared.cooking_event.signal()
#savage
shared.full_pot.wait()
```

### Timing
Cooking and taking a portion takes 1 second. Savage after eating waits for 5 seconds.
