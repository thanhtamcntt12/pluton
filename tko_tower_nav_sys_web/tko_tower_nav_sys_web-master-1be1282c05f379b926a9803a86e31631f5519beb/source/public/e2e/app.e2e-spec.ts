import { TokyoTowerPage } from './app.po';

describe('tokyo-tower App', function() {
  let page: TokyoTowerPage;

  beforeEach(() => {
    page = new TokyoTowerPage();
  });

  it('should display message saying app works', () => {
    page.navigateTo();
    expect(page.getParagraphText()).toEqual('app works!');
  });
});
