import { test } from '@playwright/test';
import fs from 'fs/promises';

interface MenuItem {
  name: string | undefined;
  price: string | undefined;
  photoUrl: string | undefined;
}

interface MenuSection {
  title: string;
  items: MenuItem[];
}

interface Restaurant {
  name: string;
  address: string;
  city: string;
  image: string | undefined;
  ranking: number; // Added ranking field
  created_at: string;
  updated_at: string;
  menu: MenuSection[];
}

const cities = ['Warszawa', 'Kraków', 'Łódź', 'Wrocław', 'Poznań', 'Gdańsk', 'Szczecin', 'Bydgoszcz', 'Lublin', 'Katowice', 'Białystok', 'Gdynia', 'Częstochowa', 'Radom', 'Toruń'];

// Generate a random ranking between 1.0 and 9.9
const generateRandomRanking = (): number => {
  return parseFloat((Math.random() * (9.9 - 1.0) + 1.0).toFixed(1));
};

// Function to scrape restaurants for a specific city
const scrapeRestaurantsForCity = async (browser: any, city: string): Promise<Restaurant[]> => {
  const context = await browser.newContext();
  const page = await context.newPage();

  await page.goto('https://wolt.com/pl/discovery');
  await page.locator('[data-test-id="allow-button"]').click();

  // Search and select the city
  await page.getByRole('button', { name: 'Warszawa' }).click();
  await page.locator('[data-test-id="AddressQueryInput"]').fill(city);
  await page.waitForTimeout(2000);
  await page.keyboard.press('Enter');
  await page.locator('[data-test-id="AddressPicker\\.ContinueButton"]').click();
  await page.waitForTimeout(6000);

  // Get restaurant links and images
  const restaurantsData = await page.$$eval(
    "//a[contains(@href, '/restaurant/')]",
    links => links.map(link => {
      const href = (link as HTMLAnchorElement).href;
      const img = link.querySelector('img') as HTMLImageElement | null;
      const imageUrl = img?.src || undefined;
      return { href, imageUrl };
    })
  );

  console.log(`Found ${restaurantsData.length} restaurants in ${city}`);

  // Remove duplicates by URL
  const uniqueRestaurantsMap = new Map<string, { href: string; imageUrl: string | undefined }>();
  restaurantsData.forEach(({ href, imageUrl }) => {
    if (!uniqueRestaurantsMap.has(href)) {
      uniqueRestaurantsMap.set(href, { href, imageUrl });
    }
  });

  const uniqueRestaurants = Array.from(uniqueRestaurantsMap.values());
  console.log(`Found ${uniqueRestaurants.length} unique restaurants in ${city}`);

  const cityRestaurants: Restaurant[] = [];

  const scrapeMenuSections = async (page: any): Promise<{ title: string; items: MenuItem[] }[]> => {
    return await page.$$eval('[data-test-id="MenuSection"]', (sections: Element[]) => {
      return sections.map(section => {
        const titleElement = section.querySelector('[data-test-id="MenuSectionTitle"] h2');
        const title = titleElement ? titleElement.textContent?.trim() || 'Unknown' : 'Unknown';

        const items = Array.from(section.querySelectorAll('[data-test-id="horizontal-item-card"]')).map((item: Element) => {
          const nameElement = item.querySelector<HTMLHeadingElement>('h3[data-test-id="horizontal-item-card-header"]');
          const priceElement = item.querySelector<HTMLSpanElement>('span[data-test-id="horizontal-item-card-price"]');
          const photoElement = item.querySelector<HTMLImageElement>('img.s1siin91');

          return {
            name: nameElement ? nameElement.textContent?.trim() : undefined,
            price: priceElement ? priceElement.textContent?.trim() : undefined,
            photoUrl: photoElement ? photoElement.src : undefined
          };
        });

        return { title, items };
      });
    });
  };

  // Function to scrape individual restaurant
  const scrapeRestaurant = async (link: string, image: string | undefined): Promise<Restaurant | null> => {
    const restaurantPage = await context.newPage();
    try {
      const navigationPromise = restaurantPage.goto(link, { timeout: 10000 });
      await Promise.race([
        navigationPromise,
        new Promise((_, reject) => setTimeout(() => reject(new Error('Page load timeout')), 10000))
      ]);

      const name = await restaurantPage.locator('//h1').textContent();
      if (!name) {
        await restaurantPage.close();
        throw new Error('Restaurant data not found');
      }

      const rawAddress = await restaurantPage.locator('//h3[text() = "Adres"]/..').textContent();
      const address = rawAddress
        ?.replace('Adres', '')
        .replace('Zobacz mapę', '')
        .trim() || 'Unknown';

      const menuSections = await scrapeMenuSections(restaurantPage);

      return {
        name: name?.trim() || 'Unknown',
        address,
        city,
        image,
        ranking: generateRandomRanking(), // Add random ranking
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString(),
        menu: menuSections
      };
    } catch (error) {
      console.error(`Error scraping ${link}: ${error.message}`);
      return null;
    } finally {
      await restaurantPage.close();
    }
  };

  // Run scraping in batches of 10 threads
  const batchSize = 10;
  for (let i = 0; i < uniqueRestaurants.length; i += batchSize) {
    const batch = uniqueRestaurants.slice(i, i + batchSize);
    const results = await Promise.all(batch.map(({ href, imageUrl }) => scrapeRestaurant(href, imageUrl)));
    cityRestaurants.push(...results.filter(Boolean) as Restaurant[]);
  }

  return cityRestaurants;
};

// Create a separate test for each city
for (const city of cities) {
  test(`scrape restaurants and menus for ${city}`, async ({ browser }) => {
    await test.setTimeout(300000);
    const cityRestaurants = await scrapeRestaurantsForCity(browser, city);

    // Save each city's data to a separate file
    const jsonFilePath = `./restaurants_${city}.json`;
    await fs.writeFile(jsonFilePath, JSON.stringify(cityRestaurants, null, 2), 'utf-8');
    console.log(`Scraped data for ${city} saved to ${jsonFilePath}`);
  });
}
