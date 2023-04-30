# Assigment 1

### Description
Implement and describe bakery algorithm.

To test our solution all you have to do is execute the main function.

### Function `process`
This function simulates a simple process where each thread prints a message with thread id and then sleeps for 1 second. 
#### Variables
+ tid      - thread id
+ n        - number of threads
+ choosing - array where i-th element is changed from 0 to 1 when i-th process is getting a ticket number. Then it's changed back to zero.
+ num      - array where each process have its own ticket number.

```python
num[i] = 1 + max(num)
```
Here i-th process gets assigned the highest ticket number corresponding to other processes.

```python
for j in range(n):
      while choosing[j] == 1:
          continue
      while num[j] != 0 and (num[j] < num[i] or (num[j] == num[i] and j < i)):
          continue
```
In the first while loop we check if there is any process thats currently getting a ticket number. In the second while loop first we look if the j-th process have a ticket number. If j-th process has lower ticket number than the current process(i-th process), then the current process is put on hold. In case where the ticket numbers are equal. The current process is put on hold if its index is higher than the other process.
After the current process finishes its critical section the ticket number is changed to 0 and the function ends.

### Bakery algorithm
Bakery algorithm works on first come first serve basis. Where process with lower ticket number enters critical section first. If the ticket numbers are equal the process with lower index enters the critical section. Critical section cannot be entered if there is a process thats currently getting assigned ticket number. Next process enters the critical section after the ticket number of current process gets set to 0.   
