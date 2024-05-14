import dropbox
import os
import base64
import requests
import config
import mimetypes
from datetime import datetime

debug = False

if debug:
    WORDPRESS_API_URL = 'https://website-dev.ologybrewing.com/wp-json/wp/v2'
    CUSTOM_WORDPRESS_API_URL = 'https://website-dev.ologybrewing.com/wp-json/ology-custom/v1'
    PLACEHOLDER_IMAGE_URL = 'https://website-dev.ologybrewing.com/wp-content/uploads/2023/04/logo_orange-1.png'
else:
    WORDPRESS_API_URL = 'https://ologybrewing.com/wp-json/wp/v2'
    CUSTOM_WORDPRESS_API_URL = 'https://ologybrewing.com/wp-json/ology-custom/v1'
    PLACEHOLDER_IMAGE_URL = 'https://ologybrewing.com/wp-content/uploads/2023/04/logo_orange-1.png'

PLACEHOLDER_IMAGE_ID = 790
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

def cache_dropbox_files(client, folder_path):
    file_cache = []
    try:
        result = client.files_list_folder(folder_path, recursive=True)
        while True:
            file_cache.extend([
                {"name": entry.name, "path": entry.path_lower, "server_modified": entry.server_modified}
                for entry in result.entries if isinstance(entry, dropbox.files.FileMetadata)
            ])
            if not result.has_more:
                break
            result = client.files_list_folder_continue(result.cursor)
    except dropbox.exceptions.ApiError as e:
        print(f"Error accessing Dropbox: {e}")
    return file_cache

def find_file_by_untappd_id(file_cache, untappd_id):
    return max(
        (file for file in file_cache if str(untappd_id) in file['name']),
        key=lambda f: f['server_modified'],
        default=None
    )

def upload_image_to_wordpress(image_path, image_data):
    filename = os.path.basename(image_path)
    mime_type, _ = mimetypes.guess_type(image_path)
    if not mime_type:
        mime_type = 'application/octet-stream'

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
        f'{WORDPRESS_API_URL}/beer_ontap/{post_id}',
        headers=headers,
        json={'featured_media': image_id}
    )

    if response.status_code != 200:
        print(f"Failed to update post {post_id}: {response.text}")

def get_posts_with_untappd_ids():
    headers = get_wordpress_headers()
    response = requests.get(f'{CUSTOM_WORDPRESS_API_URL}/beers', headers=headers)
    return response.json() if response.status_code == 200 else []

def main():
    client = get_dropbox_client()
    file_cache = cache_dropbox_files(client, DROPBOX_ROOT_FOLDER)
    posts = get_posts_with_untappd_ids()

    for post in posts:
        post_id = post['ID']
        untappd_id = post['ology_untappd_id']
        if post['featured_image_url'] not in (None, '', PLACEHOLDER_IMAGE_URL) or not untappd_id:
            continue

        matched_file = find_file_by_untappd_id(file_cache, untappd_id)
        if matched_file:
            print(f'Uploading image for post {post_id} ({untappd_id}): {matched_file["name"]}')
            _, image_data = client.files_download(matched_file['path'])
            image_id = upload_image_to_wordpress(matched_file['path'], image_data.content)
            if image_id:
                set_featured_image(post_id, image_id)
            else:
                print(f'No image found for post {post_id} ({untappd_id})')
        else:
            # Set placeholder image if no matching image is found
            set_featured_image(post_id, PLACEHOLDER_IMAGE_ID)
            print(f'No Dropbox image found for post {post_id}, using placeholder.')


if __name__ == '__main__':
    main()
