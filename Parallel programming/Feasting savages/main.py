"""This module implements feasting savages problem.

With 5 savages and 5 cooks.
 """

__author__ = "Tomáš Minárik, Tomáš Vavro, Marián Šebeňa"
__email__ = "xminarikt1@stuba.sk"
__license__ = "MIT"

from fei.ppds import Mutex, Thread, Semaphore, print, Event
from time import sleep


N = 5
COOKS = 5


class Shared:
    """Represent shared data for all threads."""
    def __init__(self):
        """Initialize an instance of Shared."""
        self.mutex = Mutex()
        self.count = 0
        self.savage_event = Event()
        self.servings = COOKS
        self.empty_pot = Event()
        self.full_pot = Semaphore(0)
        self.cooking = False
        self.cooking_event = Event()
        self.cooking_event.signal()
        self.cooks_ready = Event()
        self.cooks_ready_count = 0


def get_serving(i: int, shared: Shared):
    """Simulate savage taking portion.
    Args:
        i      -- savage's id
        shared -- shared data
    """
    sleep(1)
    print(f'Savage {i}: Takes a portion.')
    shared.servings -= 1


def savage(i: int, shared: Shared):
    """Run savage's code.
    Args:
        i      -- savage's id
        shared -- shared data
    """
    shared.cooks_ready.wait()

    while True:
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

        shared.mutex.lock()
        if shared.cooking:
            shared.mutex.unlock()
            shared.cooking_event.wait()
            shared.mutex.lock()

        print(f'Savage {i}: Number of remaining servings: {shared.servings}')
        if shared.servings == 0:
            print(f'Savage {i}: Waking up cooks.\n------------')
            shared.cooking = True
            shared.cooking_event.clear()
            shared.mutex.unlock()
            shared.empty_pot.signal()
            shared.empty_pot.clear()
            shared.full_pot.wait()
            shared.mutex.lock()

        get_serving(i, shared)
        shared.mutex.unlock()

        print(f'Savage {i} is eating.')
        sleep(5)


def cook_portion(i: int, shared: Shared):
    """Simulate cook cooking portion.
    Args:
        i      -- cook's id
        shared -- shared data
    """
    sleep(1)
    shared.servings += 1
    print(f'Cook {i}: Added a portion {shared.servings}/{COOKS}')


def cook(cook_id: int, shared: Shared):
    """Run cook's code.
    Args:
        cook_id -- cook's id
        shared  -- shared data
    """
    shared.mutex.lock()
    shared.cooks_ready_count += 1
    if shared.cooks_ready_count == COOKS:
        shared.cooks_ready.signal()
    shared.mutex.unlock()

    while True:
        shared.empty_pot.wait()
        shared.mutex.lock()
        cook_portion(cook_id, shared)
        if shared.servings == COOKS:
            print('------------')
            shared.full_pot.signal()
            shared.cooking = False
            shared.cooking_event.signal()
        shared.mutex.unlock()


def main():
    """Run main."""
    shared = Shared()
    savages = []
    cooks = []

    for i in range(N):
        savages.append(Thread(savage, i, shared))
    for j in range(COOKS):
        cooks.append(Thread(cook, j, shared))
    for t in cooks + savages:
        t.join()


if __name__ == '__main__':
    main()
