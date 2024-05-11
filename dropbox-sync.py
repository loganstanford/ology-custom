import dropbox
import os
import base64
import requests
import config
from datetime import datetime


WORDPRESS_API_URL = 'https://ologybrewing.com/wp-json/wp/v2'
CUSTOM_WORDPRESS_API_URL = 'https://ologybrewing.com/wp-json/ology-custom/v1'
DROPBOX_ROOT_FOLDER = '/Ology Brewing - Beer'

DROPBOX_ACCESS_TOKEN = config.DROPBOX_ACCESS_TOKEN
WORDPRESS_USERNAME = config.WORDPRESS_USERNAME
WORDPRESS_PASSWORD = config.WORDPRESS_PASSWORD


def get_dropbox_client():
    return dropbox.Dropbox(DROPBOX_ACCESS_TOKEN)

def get_wordpress_headers():
    credentials = f"{WORDPRESS_USERNAME}:{WORDPRESS_PASSWORD}"
    token = base64.b64encode(credentials.encode())
    return {
        'Authorization': f'Basic {token.decode("utf-8")}'
    }

def find_latest_image(client, untappd_id):
    latest_image = None
    latest_time = None

    # Recursively get all subfolders under the root folder
    def process_folder(folder_path):
        nonlocal latest_image, latest_time

        # Get all files in the folder
        result = client.files_list_folder(folder_path)

        for entry in result.entries:
            if isinstance(entry, dropbox.files.FileMetadata):
                if str(untappd_id) in entry.name:
                    if latest_time is None or entry.server_modified > latest_time:
                        latest_image = entry
                        latest_time = entry.server_modified

            elif isinstance(entry, dropbox.files.FolderMetadata):
                process_folder(entry.path_lower)

    process_folder(DROPBOX_ROOT_FOLDER)
    return latest_image

def upload_image_to_wordpress(image_path, image_data):
    filename = os.path.basename(image_path)
    headers = get_wordpress_headers()
    headers.update({
        'Content-Disposition': f'attachment; filename={filename}'
    })

    response = requests.post(
        f'{WORDPRESS_API_URL}/media',
        headers=headers,
        files={'file': (filename, image_data)}
    )

    if response.status_code == 201:
        return response.json()['id']
    else:
        print(f"Failed to upload image to WordPress: {response.text}")
        return None

def set_featured_image(post_id, image_id):
    headers = get_wordpress_headers()
    response = requests.post(
        f'{WORDPRESS_API_URL}/posts/{post_id}',
        headers=headers,
        json={'featured_media': image_id}
    )

    if response.status_code != 200:
        print(f"Failed to update post {post_id}: {response.text}")

def get_posts_with_untappd_ids():
    headers = get_wordpress_headers()
    response = requests.get(f'{CUSTOM_WORDPRESS_API_URL}/beers', headers=headers)

    if response.status_code == 200:
        return response.json()
    else:
        print(f"Failed to fetch posts: {response.text}")
        return []


def main():
    client = get_dropbox_client()
    posts = get_posts_with_untappd_ids()

    for post in posts:
        post_id = post['ID']
        untappd_id = post.get('meta', {}).get('ology_untappd_id')
        if not untappd_id:
            continue

        # Check if the post already has a featured image
        if post.get('featured_media', 0) != 0:
            continue

        latest_image_metadata = find_latest_image(client, untappd_id)

        if latest_image_metadata:
            print(f'Uploading image for post {post_id} ({untappd_id}): {latest_image_metadata.name}')
            image_data = client.files_download(latest_image_metadata.path_lower)[1].content
            image_id = upload_image_to_wordpress(latest_image_metadata.name, image_data)

            if image_id:
                set_featured_image(post_id, image_id)
        else:
            print(f'No image found for post {post_id} ({untappd_id})')

if __name__ == '__main__':
    main()
