import os
import shutil
import time
import re
from datetime import datetime
from mcrcon import MCRcon

RCON_HOST = "127.0.0.1"
RCON_PASSWORD = "dfredtrgfftdghf" # I don't remember what this was, I think you need to install it
RCON_PORT = 25575

def run_backup():
    backup_dir = "/home/minecraft-servers/EXAMPLE/backup"
    world_dir = "/home/minecraft-servers/EXAMPLE/world"
    
    # Ensure backup directory exists
    os.makedirs(backup_dir, exist_ok=True)
    
    # Generate timestamped archive name
    timestamp = datetime.now().strftime("%Y-%m-%d_%H-%M-%S")
    backup_path = os.path.join(backup_dir, f"world-backup-{timestamp}")
    
    try:
        print(f"[{datetime.now()}] Creating world backup...")
        # Create a zip archive of the world directory
        shutil.make_archive(backup_path, 'zip', world_dir)
        print(f"[{datetime.now()}] Backup successfully saved to {backup_path}.zip")
    except Exception as e:
        print(f"[{datetime.now()}] Error creating backup: {e}")
def monitor_server():
    was_populated = False
    print("Starting Minecraft empty-server backup watcher...")
    
    while True:
        try:
            with MCRcon(RCON_HOST, RCON_PASSWORD, port=RCON_PORT) as mcr:
                response = mcr.command("list")
                
            match = re.search(r'(\d+)\s+of\s+a\s+max', response)
            if match:
                current_players = int(match.group(1))
                print(f"[{datetime.now()}] Players online: {current_players} | Was populated: {was_populated}")
                
                if current_players > 0:
                    was_populated = True
                elif current_players == 0 and was_populated:
                    print(f"[{datetime.now()}] Last player left. Triggering backup...")
                    run_backup()
                    was_populated = False
            else:
                print(f"Warning: Could not parse response: {response}")
                
        except Exception as e:
            print(f"Connection error: {e}")
            
        time.sleep(10) # Temporary short sleep for testing (change back to 60 later)

if __name__ == "__main__":
    monitor_server()
