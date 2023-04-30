"""
Program represents barber shop with use of signalization and rendezvous.

University: STU Slovak Technical University in Bratislava
Faculty: FEI Faculty of Electrical Engineering and Information Technology
Year: 2023
"""


__author__ = "Tomáš Minárik, Tomáš Vavro, Marián Šebeňa"
__email__ = "xminarikt1@stuba.sk"
__license__ = "MIT"


from fei.ppds import Mutex, Thread, Semaphore, print
from time import sleep


class Shared(object):
    """Object shared for multiple threads."""

    def __init__(self):
        """
        Class constructor initialize 4 semaphores
        for barber and customer states, creates Mutex object, and
        waiting room counter
        """
        self.mutex = Mutex()
        self.waiting_room = 0
        self.customer = Semaphore(0)
        self.barber = Semaphore(0)
        self.customer_done = Semaphore(0)
        self.barber_done = Semaphore(0)


def get_haircut(i):
    """Function to print getting a haircut message and sleep"""
    print(f"Customer {i} is getting a haircut.")
    sleep(5)


def cut_hair():
    """Function to print cutting haircut message and sleep"""
    print(f"Barber is cutting hair.")
    sleep(5)


def walk(i):
    """Function to print walking away message and sleep"""
    print(f"Waiting room is full. Customer {i} is walking away.")
    sleep(6)


def growing_hair(i):
    """Function to print growing hair message and sleep"""
    print(f"Customer {i}'s hair is growing.")
    sleep(15)


def customer(i, shared):
    """Function simulating customer process"""

    while True:
        if shared.waiting_room == WAITING_ROOM_SIZE:
            walk(i)
        else:
            print(f"Customer {i} is waiting.")
            shared.mutex.lock()
            shared.waiting_room += 1
            shared.mutex.unlock()

            shared.customer.signal()
            shared.barber.wait()

            get_haircut(i)

            shared.customer_done.signal()
            shared.barber_done.wait()

            shared.mutex.lock()
            shared.waiting_room -= 1
            shared.mutex.unlock()

            growing_hair(i)


def barber(shared):
    """Function simulating barber process"""
    while True:

        shared.barber.signal()
        shared.customer.wait()

        cut_hair()

        shared.barber_done.signal()
        shared.customer_done.wait()


def main():
    shared = Shared()
    customers = []

    for i in range(NUM_CUSTOMERS):
        customers.append(Thread(customer, i, shared))
    hair_stylist = Thread(barber, shared)

    for t in customers + [hair_stylist]:
        t.join()


NUM_CUSTOMERS = 5
WAITING_ROOM_SIZE = 3

if __name__ == "__main__":
    main()
